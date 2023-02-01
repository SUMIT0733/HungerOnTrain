package com.HOT.star_0733.hot_delivery.fragment;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.CompoundButton;
import android.widget.ListView;
import android.widget.Switch;
import android.widget.Toast;

import com.HOT.star_0733.hot_delivery.CommonUtil;
import com.HOT.star_0733.hot_delivery.R;
import com.HOT.star_0733.hot_delivery.ViewOrder;
import com.HOT.star_0733.hot_delivery.adapter.OrderDetailAdapter;
import com.HOT.star_0733.hot_delivery.model.OrderDetail;
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

public class HomeFragment extends Fragment {

      View view;
      ListView list;
      AsyncHttpClient client;
      ProgressDialog dialog;
      ArrayList<OrderDetail> arrayList;
      Switch status;

      @Nullable
      @Override
      public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
            view = inflater.inflate(R.layout.fragment_home,container,false);

            list = view.findViewById(R.id.list);
            client = new AsyncHttpClient();
            status = view.findViewById(R.id.status);
          dialog = new ProgressDialog(getActivity());
          dialog.setMessage("Please wait...");
          dialog.setCancelable(false);
          arrayList = new ArrayList<>();

            return view;
      }

      @Override
      public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
            super.onViewCreated(view, savedInstanceState);

          list.setOnItemClickListener(new AdapterView.OnItemClickListener() {
              @Override
              public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                  getActivity().finish();
                  startActivity(new Intent(getActivity(), ViewOrder.class).putExtra("id",arrayList.get(i).getOrder()));
              }
          });

          status.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
              @Override
              public void onCheckedChanged(CompoundButton compoundButton, boolean b) {
                  setStatus(b);
              }
          });
      }

    private void setStatus(final boolean b) {
        RequestParams params = new RequestParams();
        if(b)
            params.put("status",1);
        else
            params.put("status",0);

        params.put("id",getActivity().getSharedPreferences(CommonUtil.Pref, Context.MODE_PRIVATE).getString("id","1"));
        params.put("function","updateStatus");
        client.post(CommonUtil.url+"delivery_person.php",params,new JsonHttpResponseHandler(){
            @Override
            public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                if(b)
                    Toast.makeText(getActivity(), "You are now online", Toast.LENGTH_SHORT).show();
                else
                    Toast.makeText(getActivity(), "You are now offline", Toast.LENGTH_SHORT).show();
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                super.onFailure(statusCode, headers, throwable, errorResponse);
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                super.onFailure(statusCode, headers, responseString, throwable);
            }
        });
    }

    @Override
    public void onResume() {
        super.onResume();
        boolean onStatus = getActivity().getSharedPreferences("HOT_STATUS",Context.MODE_PRIVATE).getBoolean("status",true);
        if(onStatus == true){
            status.setChecked(true);
        }else
        {
            status.setChecked(false);
        }
        arrayList.clear();
        loadList();
    }

    private void loadList() {
          arrayList.clear();
        RequestParams params = new RequestParams();
        params.put("id",getActivity().getSharedPreferences(CommonUtil.Pref, Context.MODE_PRIVATE).getString("id","0"));
        client.post(CommonUtil.url+"getpendingorder.php",params,new JsonHttpResponseHandler(){
            @Override
            public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                try {
                    if(response.getString("responce").equals("success")){
                        JSONArray array = response.getJSONArray("data");
                        Log.d("order",response.toString());
                        if(array.length() > 0) {
                            for (int i = 0; i < array.length(); i++) {
                                JSONObject object = array.getJSONObject(i);
                                arrayList.add(new OrderDetail(object.getString("order_id"),
                                      object.getString("restaurant_name"),
                                      object.getString("order_time")));
                            }

                            OrderDetailAdapter adapter = new OrderDetailAdapter(getActivity(),arrayList);
                            list.setAdapter(adapter);

                        }
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {

            }

            @Override
            public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {

            }

            @Override
            public void onStart() {
                dialog.show();
            }

            @Override
            public void onFinish() {
                dialog.dismiss();
            }
        });
      }
}
