package com.HOT.star_0733.hot_delivery;

import android.app.ActivityManager;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.net.Uri;
import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.Locale;

import cc.cloudist.acplibrary.ACProgressConstant;
import cc.cloudist.acplibrary.ACProgressFlower;
import cz.msebera.android.httpclient.Header;
import cz.msebera.android.httpclient.client.methods.RequestBuilder;
import in.shadowfax.proswipebutton.ProSwipeButton;

public class ViewOrder extends AppCompatActivity {

    String id;
    AsyncHttpClient client;
    ACProgressFlower dialog;

    String rest_contact,customer_contact;
    String cart_data=" ";
    TextView order_id,restaurant_name,restaurant_address,restaurant_contact;
    TextView delivery_name,delivery_contact,delivery_address,delivery_city,delivery_time;
    TextView cart_item,otp;
    ProSwipeButton delivery;
    Button rest_map,station_map;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_view_order);

        id = getIntent().getStringExtra("id");
        initView();

        getOrder(id);

        delivery.setOnSwipeListener(new ProSwipeButton.OnSwipeListener() {
            @Override
            public void onSwipeConfirm() {
                RequestParams params = new RequestParams();
                params.put("id",id);
                client.post(CommonUtil.url+"order_delivery.php",params,new JsonHttpResponseHandler(){
                    @Override
                    public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                        try {
                            if(response.getString("responce").equals("success")){
                                delivery.showResultIcon(true);
                                finish();
                                startActivity(new Intent(ViewOrder.this,Home.class));
                            }else {
                                delivery.showResultIcon(false);
                                Toast.makeText(ViewOrder.this, "Something went wrong.", Toast.LENGTH_SHORT).show();
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
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
        });
    }

    private void getOrder(String id) {

        RequestParams params = new RequestParams();
        params.put("id",id);
        client.post(CommonUtil.url+"getorderdetail.php",params,new JsonHttpResponseHandler(){
            @Override
            public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                Log.d("---data---",response.toString());
                try {
                    JSONArray array = response.getJSONArray("data");
                    final JSONObject object = array.getJSONObject(0);

                    order_id.setText("Order # "+object.getString("order_id"));
                    restaurant_name.setText(object.getString("restaurant_name"));
                    restaurant_address.setText(object.getString("restaurant_address"));
                    rest_contact = object.getString("contact_no");
                    restaurant_contact.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View view) {
                            Intent intent = new Intent(Intent.ACTION_DIAL);
                            intent.setData(Uri.parse("tel: "+rest_contact));
                            startActivity(intent);
                        }
                    });

                    delivery_name.setText(object.getString("end_customer_name"));
                    otp.setText(object.getString("otp"));
                    delivery_address.setText(object.getString("delivery_address"));
                    delivery_city.setText(object.getString("delivery_station"));
                    delivery_time.setText(object.getString("order_time"));
                    customer_contact = object.getString("end_customer_contact");
                    delivery_contact.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View view) {
                            Intent intent = new Intent(Intent.ACTION_DIAL);
                            intent.setData(Uri.parse("tel: "+customer_contact));
                            startActivity(intent);
                        }
                    });

                    rest_map.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View view) {
                            try {
                                String rest_str = "http://maps.google.co.in/maps?q="+object.getString("lat")+","+object.getString("lng");
                                Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(rest_str));
                                startActivity(intent);
                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }
                    });

                    station_map.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View view) {

                            try {
                                String station_str = "http://maps.google.co.in/maps?q=" + object.getString("delivery_station")+" railway station";
                                Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(station_str));
                                startActivity(intent);

                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }
                    });
                    JSONArray cart = object.getJSONArray("cart");
                    for(int i=0;i<cart.length();i++){
                        JSONObject cart_obj = cart.getJSONObject(i);
                        cart_data += "( "+(i+1)+" ) "+cart_obj.getString("food_item_name")+"  * "+cart_obj.getString("unit")+"\n ";
                    }
                    cart_item.setText(cart_data);

                } catch (JSONException e) {
                    e.printStackTrace();
                }

            }

            @Override
            public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {

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

    private void initView() {
        client = new AsyncHttpClient();
        dialog = new ACProgressFlower.Builder(ViewOrder.this)
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

        order_id = findViewById(R.id.order_id);
        restaurant_name = findViewById(R.id.restaurant_name);
        restaurant_address = findViewById(R.id.restaurant_address);
        restaurant_contact = findViewById(R.id.restaurant_contact);

        delivery_name = findViewById(R.id.delivery_name);
        delivery_contact = findViewById(R.id.delivery_contact);
        delivery_address = findViewById(R.id.delivery_address);
        delivery_city = findViewById(R.id.delivery_city);
        delivery_time = findViewById(R.id.delivery_time);
        cart_item = findViewById(R.id.cart_item);
        delivery = findViewById(R.id.delivery);
        otp = findViewById(R.id.otp);

        rest_map = findViewById(R.id.rest_map);
        station_map = findViewById(R.id.station_map);
    }


    private boolean isMyServiceRunning(Class<?> serviceClass) {
        ActivityManager manager = (ActivityManager) getSystemService(Context.ACTIVITY_SERVICE);
        for (ActivityManager.RunningServiceInfo service : manager.getRunningServices(Integer.MAX_VALUE)) {
            if (serviceClass.getName().equals(service.service.getClassName())) {
                return true;
            }
        }
        return false;
    }

    @Override
    protected void onResume() {
        super.onResume();
        if(!isMyServiceRunning(LiveLocationUpdate.class)){
            startService(new Intent(getApplicationContext(),LiveLocationUpdate.class));
//            Toast.makeText(Home.this, "SERVICE START", Toast.LENGTH_SHORT).show();
        }
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        finish();
        startActivity(new Intent(ViewOrder.this,Home.class));
    }
}
