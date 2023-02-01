package com.HOT.star_0733.hottrain.fragment;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.AlertDialog;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.Toast;

import com.HOT.star_0733.hottrain.Adapter.HistoryAdapter;
import com.HOT.star_0733.hottrain.CommonUtil;
import com.HOT.star_0733.hottrain.R;
import com.HOT.star_0733.hottrain.Restaurant_list;
import com.HOT.star_0733.hottrain.ViewOrder;
import com.HOT.star_0733.hottrain.model.HistoryModel;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.Locale;

import cc.cloudist.acplibrary.ACProgressConstant;
import cc.cloudist.acplibrary.ACProgressFlower;
import cz.msebera.android.httpclient.Header;

public class OrderHistoryFragment extends Fragment {

      View view;
      ListView listView;
      ArrayList<HistoryModel> list;
      HistoryAdapter adapter;
      SwipeRefreshLayout swipe;
      boolean wifi,data;
      ConnectivityManager connectivityManager;
      SharedPreferences sharedPreferences;
      String id;
      AsyncHttpClient client;
      ACProgressFlower dialog;

      @Nullable
      @Override
      public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
            view = inflater.inflate(R.layout.orderhistory,container,false);
            connectivityManager = ((ConnectivityManager)getActivity(). getSystemService(Context.CONNECTIVITY_SERVICE));
            getPref();
            return view;
      }
      
      @Override
      public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {

            dialog = new ACProgressFlower.Builder(getActivity())
                        .direction(ACProgressConstant.DIRECT_CLOCKWISE)
                        .themeColor(R.color.grey_700)
                        .bgColor(Color.WHITE)
                        .textAlpha(1)
                        .text("Loading...")
                        .textColor(Color.BLACK)
                        .speed(15)
                        .bgAlpha(1)
                        .fadeColor(Color.WHITE)
                        .build();
            listView = view.findViewById(R.id.list);
            swipe = view.findViewById(R.id.swipe);
            swipe.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
                  @Override
                  public void onRefresh() {
                        swipe.setRefreshing(false);
                        wifi = getWifi();
                        data = getData();

                        if(!wifi  && !data){
                              Toast.makeText(getActivity(), "No Internet Connection.", Toast.LENGTH_SHORT).show();
                              AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
                              builder.setTitle("Error")
                                          .setMessage("No internet connection.Try again.")
                                          .setCancelable(false)
                                          .setNegativeButton("ok", new DialogInterface.OnClickListener() {
                                                @Override
                                                public void onClick(DialogInterface dialogInterface, int i) {
                                                      dialogInterface.dismiss();
                                                }
                                          }).show();
                        }else {
                              loadList(id);
                        }
                  }
            });

            wifi = getWifi();
            data = getData();
            if(!wifi  && !data){
                  Toast.makeText(getActivity(), "No Internet Connection.", Toast.LENGTH_SHORT).show();
                  AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
                  builder.setTitle("Error")
                              .setMessage("No internet connection.Try again.")
                              .setCancelable(false)
                              .setNegativeButton("ok", new DialogInterface.OnClickListener() {
                                    @Override
                                    public void onClick(DialogInterface dialogInterface, int i) {
                                          dialogInterface.dismiss();
                                    }
                              }).show();
            }else {
                  loadList(id);
            }

            listView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                  @Override
                  public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                        startActivity(new Intent(getActivity(), ViewOrder.class).putExtra("order",list.get(i).getOrder()));
                  }
            });


      }

      private void loadList(String id) {
            list = new ArrayList<>();
            list.clear();

            client = new AsyncHttpClient();
            RequestParams params = new RequestParams();
            params.put("id",id);
            client.get(CommonUtil.url+"gethistory.php",params,new JsonHttpResponseHandler(){
                  @Override
                  public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                        dialog.dismiss();
                        try {
                              String responce = response.getString("responce");
                              if(responce.equals("Success")){
                                    Log.d("--orders---",response.toString());
                                    JSONArray array = response.getJSONArray("data");
                                    for(int i=0;i<array.length();i++){
                                          JSONObject object = array.getJSONObject(i);
                                          list.add(new HistoryModel(object.getString("restaurant_name"),object.getString("order_amount"),"order # "+object.getString("order_id"),getDate(object.getString("create_date")),object.getString("effect_amount")));
                                    }
                                    if(list.isEmpty()){
                                          Toast.makeText(getActivity(), "No Order found.", Toast.LENGTH_SHORT).show();
                                    }else {
                                          adapter = new HistoryAdapter(getActivity(),list);
                                          listView.setAdapter(adapter);
                                    }
                              }
                              else {
                                    Toast.makeText(getActivity(), "Error occured! Try after sometime.", Toast.LENGTH_SHORT).show();
                              }
                        } catch (JSONException e) {
                              e.printStackTrace();
                        }

                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                        super.onFailure(statusCode, headers, throwable, errorResponse);
                        dialog.dismiss();
                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                        super.onFailure(statusCode, headers, responseString, throwable);
                        dialog.dismiss();
                  }

                  @Override
                  public void onStart() {
                        super.onStart();
                        dialog.show();
                  }

                  @Override
                  public void onFinish() {
                        super.onFinish();
                        dialog.dismiss();
                  }
            });

      }

      public  String getDate(String create_date) {
            DateFormat originalFormat = new SimpleDateFormat("yyyy-MM-dd H:m:s", Locale.ENGLISH);
            DateFormat targetFormat = new SimpleDateFormat("MMM dd,yyyy");
            String formattedDate = "";
            Date date = null;
            try {
                  date = originalFormat.parse(create_date);
                  formattedDate = targetFormat.format(date);
            } catch (ParseException e) {
                  e.printStackTrace();
            }
            return formattedDate;
      }

      private boolean getWifi() {
            NetworkInfo networkInfo = connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI);
            wifi = networkInfo.isConnected();
            return wifi;
      }

      private boolean getData(){
            NetworkInfo networkInfo1 = connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE);
            data = networkInfo1.isConnected();
            return data;
      }

      public void getPref() {
            sharedPreferences = getActivity().getSharedPreferences(Restaurant_list.MyPREFERENCES, Context.MODE_PRIVATE);
            id = sharedPreferences.getString("id", "100");
      }
}
