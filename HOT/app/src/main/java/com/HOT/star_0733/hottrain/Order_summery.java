package com.HOT.star_0733.hottrain;

import android.content.Intent;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.HOT.star_0733.hottrain.R;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class Order_summery extends AppCompatActivity {

      String data;
      ActionBar actionBar;
      TextView order_id,item_detail,amount,restaurant_name,restaurant_address,restaurant_city,restaurant_contact,user_name,user_contact,date,station,address;
      LinearLayout offer_content;
    TextView promocode,original_amt,discount_amt,delivery_amt,final_amt;
      @Override
      protected void onCreate(Bundle savedInstanceState) {
            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_order_summery);

            setScreen();
            initView();

//            data = "{\"rest\":[{\"city_name\":\"SURAT\",\"restaurant_pincode\":\"388456\",\"contact_no\":\"7879898989\",\"restaurant_name\":\"Square cafe\",\"restaurant_address\":\"a-233.Bakrol Square, Surat\"}],\"delivery\":[{\"offer_code\":NOCODE,\"user_name\":\"Sumit Monapara\",\"contact\":\"1555558555\",\"address\":\"KARNAVATI EXP  ( 12933 ) , D2 - 1\",\"station\":\"SURAT\",\"instruction\":\"No Instruction\",\"time\":\"2019-04-11 17:35:00\",\"amount\":\"120\",\"order_time\":\"2019-04-11 18:15:40\",\"offer_id\":\"10\",\"effect_amount\":\"60\"}],\"responce\":\"Success\",\"cart_responce\":\"Success\",\"cart\":[{\"cart_id\":\"80\",\"user_id\":\"1\",\"rest_id\":\"8\",\"food_id\":\"9\",\"cuisine\":\"Burgers\",\"price\":\"60\",\"unit\":\"2\",\"food_item_name\":\"Aloo tikki burgers\"}],\"delete_reponce\":\"Success\",\"order_inventery_responce\":\"Success\",\"order_id\":\"20190411103\"}";

//            data = " {\"responce\":\"Success\",\"rest\":[[{\"city_name\":\"ANAND\",\"restaurant_pincode\":\"0\",\"contact_no\":\"7573887869\",\"restaurant_name\":\"Kabir Restaurant\",\"restaurant_address\":\"\"}]],\"cart_responce\":\"Success\",\"delivery\":[{\"user_name\":\"Sumit Monapara\",\"contact\":\"4546464646\",\"address\":\"KARNAVATI EXP  ( 12933 ) , TG - 45\",\"station\":\"ANAND JN\",\"instruction\":\"email sumitmonapara@gmail.com   id   1 name  sumit rest_id 1\",\"time\":\"19:59\",\"amount\":\"120\",\"order_time\":\"2019-02-26 14:30:11\"}],\"cart\":[{\"cart_id\":\"44\",\"user_id\":\"1\",\"rest_id\":\"1\",\"food_id\":\"1\",\"cuisine\":\"gujarati\",\"price\":\"120\",\"unit\":\"1\"}],\"delete_reponce\":\"Success\",\"order_inventery_responce\":\"Success\",\"order_id\":\"26022019002\"}";

            Intent intent = getIntent();
            data = intent.getStringExtra("data");
            setDate(data);

      }

      public void setDate(String data) {
            try {
                  JSONObject object = new JSONObject(data);
                  order_id.setText("Order id : "+object.getString("order_id"));

                  JSONArray array = object.getJSONArray("rest");
                  JSONObject rest = array.getJSONObject(0);
                  restaurant_name.setText(rest.getString("restaurant_name"));
                  restaurant_address.setText(rest.getString("restaurant_address"));
                  restaurant_city.setText(rest.getString("city_name")+"  -  "+rest.getString("restaurant_pincode"));
                  restaurant_contact.setText("Contact : "+rest.getString("contact_no"));

                  array = object.getJSONArray("delivery");
                  JSONObject user = array.getJSONObject(0);

                  user_name.setText(user.getString("user_name"));
                  user_contact.setText(user.getString("contact"));
                  date.setText(user.getString("order_time"));
                  station.setText(user.getString("station")+"  #  "+user.getString("time"));
                  address.setText(user.getString("address"));
//                  amount.setText("Order Amount :  "+user.getString("amount"));

                  if(user.getString("offer_code").equals("NOCODE")){
                      offer_content.setVisibility(View.GONE);
                      amount.setVisibility(View.VISIBLE);
                      int tmp = Integer.parseInt(user.getString("amount")) + 10;
                      amount.setText("Order Amount :  \u20b9 "+tmp);
                  }else {
                      amount.setVisibility(View.GONE);
                      offer_content.setVisibility(View.VISIBLE);
                      promocode.setText(user.getString("offer_code"));
                      original_amt.setText("\u20b9 "+user.getString("amount"));
                      int ori = Integer.parseInt(user.getString("amount"));
                      int effect = Integer.parseInt(user.getString("effect_amount"));
                      discount_amt.setText("- \u20b9 "+String.valueOf(ori - effect));
                      final_amt.setText("\u20b9 "+String.valueOf(effect + 10));
                  }


                  array = object.getJSONArray("cart");
                  String detail = " ";
                  for(int i=0;i<array.length();i++) {
                        JSONObject cart = array.getJSONObject(i);
                        detail += (i+1)+".  "+cart.getString("food_item_name")+" ( "+cart.getString("price")+"  X  "+cart.getString("unit")+"  )\n";
                  }
                  item_detail.setText(detail);
            } catch (JSONException e) {
                  e.printStackTrace();
            }
      }

      public void initView() {
            order_id = findViewById(R.id.order_id);
            item_detail = findViewById(R.id.item_detail);
            amount = findViewById(R.id.amount);
            restaurant_name = findViewById(R.id.restaurant_name);
            restaurant_address = findViewById(R.id.restaurant_address);
            restaurant_city = findViewById(R.id.restaurant_city);
            restaurant_contact = findViewById(R.id.restaurant_contact);
            user_name = findViewById(R.id.user_name);
            user_contact = findViewById(R.id.user_contact);
            date = findViewById(R.id.date);
            station = findViewById(R.id.station);
            address = findViewById(R.id.address);

          offer_content = findViewById(R.id.offer_content);
          original_amt = findViewById(R.id.original_amt);
          discount_amt = findViewById(R.id.discount_amt);
          delivery_amt = findViewById(R.id.delivery_amt);
          final_amt = findViewById(R.id.final_amt);
          promocode = findViewById(R.id.promocode);
      }

      public void setScreen() {
            actionBar = getSupportActionBar();
            actionBar.setHomeAsUpIndicator(R.drawable.back);
            actionBar.setDisplayHomeAsUpEnabled(true);
            actionBar.setHomeButtonEnabled(true);
            actionBar.setElevation(25);
            actionBar.setTitle("Order summary");
      }

      @Override
      public boolean onOptionsItemSelected(MenuItem item) {
            switch (item.getItemId()){
                  case android.R.id.home:
                        finish();
                        Intent intent = new Intent(this, Home.class);
                        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                        startActivity(intent);
                        break;
            }
            return true;
      }

      @Override
      public void onBackPressed() {
            finish();
            Intent intent = new Intent(this, Home.class);
            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
            startActivity(intent);
      }
}
