package com.HOT.star_0733.hot_delivery.fragment;

import android.content.Context;
import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.CompoundButton;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.Switch;
import android.widget.TextView;
import android.widget.Toast;

import com.HOT.star_0733.hot_delivery.CommonUtil;
import com.HOT.star_0733.hot_delivery.R;
import com.HOT.star_0733.hot_delivery.Register;
import com.HOT.star_0733.hot_delivery.adapter.OrderAdapter;
import com.HOT.star_0733.hot_delivery.model.OrderModel;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import cc.cloudist.acplibrary.ACProgressConstant;
import cc.cloudist.acplibrary.ACProgressFlower;
import cz.msebera.android.httpclient.Header;

public class GetOrderFragment extends Fragment {

      ListView listview;
      ArrayList<OrderModel> list;
      TextView textinfo;
      View view;
      Switch status;
      AsyncHttpClient client;
      ACProgressFlower dialog;
      RelativeLayout main_content;
      @Nullable
      @Override
      public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
            view = inflater.inflate(R.layout.order_fragment, container, false);
            initView();
            return view;
      }

      private void initView() {
            status = view.findViewById(R.id.status);
            listview = view.findViewById(R.id.listview);
            list = new ArrayList<>();
            client = new AsyncHttpClient();

            dialog = new ACProgressFlower.Builder(getActivity())
                        .direction(ACProgressConstant.DIRECT_CLOCKWISE)
                        .themeColor(R.color.grey_700)
                        .bgColor(Color.WHITE)
                        .textAlpha(1)
                        .text("Please wait...")
                        .textColor(Color.BLACK)
                        .speed(15)
                        .bgAlpha(1)
                        .fadeColor(Color.WHITE)
                        .build();

            main_content = view.findViewById(R.id.main_content);
      }

      @Override
      public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
            getStatus();
            list.add(new OrderModel("Dhokla","3","120",1));
            list.add(new OrderModel("Thali","4","20",0));
            list.add(new OrderModel("Rise","1","200",1));

            OrderAdapter adapter = new OrderAdapter(getActivity(),list);
            listview.setAdapter(adapter);

            status.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
                  @Override
                  public void onCheckedChanged(CompoundButton compoundButton, boolean b) {
                        if(b){
                              updateStatus(1);
                        }else {
                              updateStatus(0);
                        }
                  }
            });
      }

      private void getStatus() {
            RequestParams params = new RequestParams();
            params.put("id",getActivity().getSharedPreferences(CommonUtil.Pref, Context.MODE_PRIVATE).getString("id","1"));
            params.put("function","getStatus");
            client.post(CommonUtil.url+"delivery_person.php",params,new JsonHttpResponseHandler(){
                  @Override
                  public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                        super.onSuccess(statusCode, headers, response);
                        dialog.dismiss();
                        try {
                              Log.d("-----status",response.toString());
                              if(response.getString("responce").equals("success")){
                                    if(response.getString("status").equals("1")){
                                          status.setChecked(true);
                                          main_content.setVisibility(View.VISIBLE);
                                    }else {
                                          status.setChecked(false);
                                          main_content.setVisibility(View.GONE);
                                    }
                              }else {
                                    Toast.makeText(getActivity(), "Something went wrong .", Toast.LENGTH_SHORT).show();
                                    main_content.setVisibility(View.GONE);
                              }
                        } catch (JSONException e) {
                              e.printStackTrace();
                        }

                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                        super.onFailure(statusCode, headers, throwable, errorResponse);
                        dialog.dismiss();
                        Toast.makeText(getActivity(), "Something went wrong", Toast.LENGTH_SHORT).show();
                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                        super.onFailure(statusCode, headers, responseString, throwable);
                        dialog.dismiss();
                        Toast.makeText(getActivity(), "Something went wrong", Toast.LENGTH_SHORT).show();
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

      private void updateStatus(int i) {
            RequestParams params = new RequestParams();
            params.put("status",i);
            params.put("id",getActivity().getSharedPreferences(CommonUtil.Pref, Context.MODE_PRIVATE).getString("id","1"));
            params.put("function","updateStatus");
            client.post(CommonUtil.url+"delivery_person.php",params,new JsonHttpResponseHandler(){
                  @Override
                  public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                        super.onSuccess(statusCode, headers, response);
                        dialog.dismiss();
                        try {
                              if(response.getString("responce").equals("Assigned")){
                                    Toast.makeText(getActivity(), "You can't go offline while order is assigned to you.", Toast.LENGTH_SHORT).show();
                                    status.setChecked(true);
                                    main_content.setVisibility(View.VISIBLE);
                              }else {
                                    if(response.getString("status").equals("1")){
                                          Toast.makeText(getActivity(), "You are now online.", Toast.LENGTH_SHORT).show();
                                          main_content.setVisibility(View.VISIBLE);
                                    }else {
                                          Toast.makeText(getActivity(), "You are now offline.", Toast.LENGTH_SHORT).show();
                                          main_content.setVisibility(View.GONE);
                                    }
                              }
                        } catch (JSONException e) {
                              e.printStackTrace();
                        }

                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                        super.onFailure(statusCode, headers, throwable, errorResponse);
                        dialog.dismiss();
                        Toast.makeText(getActivity(), "Something went wrong", Toast.LENGTH_SHORT).show();
                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                        super.onFailure(statusCode, headers, responseString, throwable);
                        dialog.dismiss();
                        Toast.makeText(getActivity(), "Something went wrong", Toast.LENGTH_SHORT).show();
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
}
