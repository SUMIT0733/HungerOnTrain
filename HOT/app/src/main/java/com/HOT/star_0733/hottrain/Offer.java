package com.HOT.star_0733.hottrain;

import android.content.Context;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.Toast;

import com.HOT.star_0733.hottrain.Adapter.OfferAdapter;
import com.HOT.star_0733.hottrain.model.OfferModel;
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

public class Offer extends AppCompatActivity {

    ListView offer_list;
    ActionBar actionBar;
    AsyncHttpClient client;
    ACProgressFlower dialog;
    ArrayList<OfferModel> list;
    SharedPreferences preferences;
    SharedPreferences.Editor editor;
    String total;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_offer);

//        Toast.makeText(this, ""+getIntent().getStringExtra("total"), Toast.LENGTH_SHORT).show();
        total = getIntent().getStringExtra("total");

        preferences = getSharedPreferences("OFFER",MODE_PRIVATE);
        editor = preferences.edit();

        initView();
        setScreen();

        loadOffer();

        offer_list.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                validateOffer(list.get(i).getOffer_id(),list.get(i).getOffer_code());
            }
        });
    }

    private void validateOffer(final int offer_id, final String offer_code) {
        RequestParams params = new RequestParams();
        params.put("id",offer_id);
        params.put("total",total);
        params.put("user_id",getSharedPreferences("HOT", Context.MODE_PRIVATE).getString("id"," "));
        client.post(CommonUtil.url+"validateOffer.php",params,new JsonHttpResponseHandler(){
            @Override
            public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                try {
                    Log.d("---data---",response.toString());
                    if(response.getString("responce").equals("success")){
                        editor.putString("offer_id", String.valueOf(offer_id));
//                        Toast.makeText(Offer.this, ""+offer_id, Toast.LENGTH_SHORT).show();
                        editor.putString("code",offer_code);
                        editor.putString("effect",response.getString("effect"));
                        editor.putBoolean("offer_apply",true);
                        editor.apply();

                    }else if(response.getString("responce").equals("Maximum usage of promocode exceeds.")){
                        Toast.makeText(Offer.this, "Limit exceed.", Toast.LENGTH_SHORT).show();
                    }else{
                        Toast.makeText(Offer.this, ""+response.getString("responce"), Toast.LENGTH_SHORT).show();
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                Toast.makeText(Offer.this, "Something went wrong.Try again.", Toast.LENGTH_SHORT).show();
            }

            @Override
            public void onStart() {
                dialog.setTitle("validating...");
                dialog.show();
            }

            @Override
            public void onFinish() {
                dialog.dismiss();
                finish();
            }
        });
    }

    private void loadOffer() {
        client.post(CommonUtil.url+"fetchoffer.php",new JsonHttpResponseHandler(){
            @Override
            public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                try {
                    if(response.getString("responce").equals("success")){
                        Log.d("---offer---",response.toString());
                        list.clear();
                        String key;
                        JSONArray array = response.getJSONArray("data");
                        for(int i=0;i<array.length();i++){
                            JSONObject object = array.getJSONObject(i);
                            list.add(new OfferModel(Integer.parseInt(object.getString("offer_id")),
                                  Integer.parseInt(object.getString("unit")),
                                  Integer.parseInt(object.getString("discount_upto_rs")),
                                  Integer.parseInt(object.getString("min_order_amount")),
                                  Integer.parseInt(object.getString("usage_per_user")),
                                  object.getString("offer_name"),
                                  object.getString("description"),
                                  object.getString("offer_code"),
                                  Integer.parseInt(object.getString("percentage_or_flat"))));

                        }

                        OfferAdapter adapter = new OfferAdapter(Offer.this,list);
                        offer_list.setAdapter(adapter);


                    }else if(response.getString("responce").equals("No Offer Available")){
                        Toast.makeText(Offer.this, "No Offer Available", Toast.LENGTH_SHORT).show();
                        finish();
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {

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

    private void initView() {
        offer_list = findViewById(R.id.offer_list);
        client = new AsyncHttpClient();

        dialog = new ACProgressFlower.Builder(Offer.this)
              .direction(ACProgressConstant.DIRECT_CLOCKWISE)
              .themeColor(R.color.grey_700)
              .bgColor(Color.WHITE)
              .textAlpha(1)
              .bgAlpha(1)
              .text("Please wait...")
              .textColor(Color.BLACK)
              .speed(15)
              .fadeColor(Color.WHITE)
              .build();

        list = new ArrayList<>();
    }

    public void setScreen() {
        actionBar = getSupportActionBar();
        actionBar.setHomeAsUpIndicator(R.drawable.back);
        actionBar.setDisplayHomeAsUpEnabled(true);
        actionBar.setHomeButtonEnabled(true);
        actionBar.setElevation(25);
        actionBar.setTitle("Offers");
        //getWindow().setStatusBarColor(getResources().getColor(R.color.gray));
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()){
            case android.R.id.home:
                finish();
                break;
        }
        return true;
    }
}
