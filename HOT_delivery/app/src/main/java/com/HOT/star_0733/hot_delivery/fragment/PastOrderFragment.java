package com.HOT.star_0733.hot_delivery.fragment;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
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

import cz.msebera.android.httpclient.Header;

public class PastOrderFragment extends Fragment {

      View view;
      ListView list;
      AsyncHttpClient client;
      ProgressDialog dialog;
      ArrayList<OrderDetail> arrayList;
      @Nullable
      @Override
      public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
            view = inflater.inflate(R.layout.past_order_fragment, container, false);
          list = view.findViewById(R.id.past_list);
          client = new AsyncHttpClient();
          dialog = new ProgressDialog(getActivity());
          dialog.setMessage("Please wait...");
          dialog.setCancelable(false);
          arrayList = new ArrayList<>();

            return view;
      }

      @Override
      public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {

      }

    @Override
    public void onResume() {
        super.onResume();
        loadList();
    }

    private void loadList() {
        arrayList.clear();
        RequestParams params = new RequestParams();
        params.put("id",getActivity().getSharedPreferences(CommonUtil.Pref, Context.MODE_PRIVATE).getString("id","0"));
        client.post(CommonUtil.url+"getdeliverypastorder.php",params,new JsonHttpResponseHandler(){
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
