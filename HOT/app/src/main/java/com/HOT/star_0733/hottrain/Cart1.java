package com.HOT.star_0733.hottrain;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;


import com.HOT.star_0733.hottrain.Adapter.CartAdapter;
import com.HOT.star_0733.hottrain.R;

import com.HOT.star_0733.hottrain.model.CartData;

import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;

import cc.cloudist.acplibrary.ACProgressConstant;
import cc.cloudist.acplibrary.ACProgressFlower;
import cz.msebera.android.httpclient.Header;

public class Cart1 extends AppCompatActivity {

    ListView cart_items;
    ImageView back;
    TextView restaurant_name,cooking_instruction_text,total_amount,check_out;
    EditText cooking_instruction_edit;
    int price=0,unit=0,total=0;
    ActionBar actionBar;

    AsyncHttpClient client;
    RequestParams params;

    ACProgressFlower dialog;

    SharedPreferences preferences;
    SharedPreferences.Editor editor;
    public static final String MyPREFERENCES = "HOT";

    int id=0;
    String restaurant,rest_id;
    ArrayList<CartData> arrayList = new ArrayList<>();
    ArrayAdapter<CartData> adapter;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_cart1);

        preferences = getSharedPreferences(MyPREFERENCES, Context.MODE_PRIVATE);
        editor = preferences.edit();

          dialog = new ACProgressFlower.Builder(Cart1.this)
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


        id = Integer.parseInt(preferences.getString("id","1"));
        //Toast.makeText(this, ""+id, Toast.LENGTH_SHORT).show();

        setScreen();
        initView();
        loadList(id);

        total_amount.setText(""+getSharedPreferences(MyPREFERENCES, Context.MODE_PRIVATE).getInt("total_amt",1));

        getSharedPreferences("OFFER",MODE_PRIVATE).edit().clear().commit();

        check_out.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if(arrayList.size() > 0)
                    startActivity(new Intent(Cart1.this,CheckOut.class)
                                .putExtra("total",total_amount.getText().toString().trim())
                                .putExtra("rest_id",rest_id));
                else
                    Toast.makeText(Cart1.this, "Please add some food to cart.", Toast.LENGTH_SHORT).show();
            }
        });

    }

      @Override
      protected void onRestart() {
            super.onRestart();
            loadList(id);
            getSharedPreferences("OFFER",MODE_PRIVATE).edit().clear().commit();

      }

      public  void loadList(int id) {
          arrayList.clear();
        params.put("id",id);
        total = 0;
        client.post(CommonUtil.url+"loadcart.php",params,new JsonHttpResponseHandler(){
            @Override
            public void onStart() {
                dialog.show();
            }
            @Override
            public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                super.onSuccess(statusCode, headers, response);

                try{
                    Log.d("-----response---",response.getString("responce"));
                    String res = response.getString("responce");

                    if(res.equals("success")){
                        restaurant = response.getString("restaurant");
                        restaurant_name.setText(restaurant);
                        rest_id = response.getString("rest_id");

                        JSONArray array = response.getJSONArray("data");
                        for (int i=0;i<array.length();i++){
                            JSONObject object = array.getJSONObject(i);

                            arrayList.add(new CartData(Integer.parseInt(object.getString("cart_id")),
                                    Integer.parseInt(object.getString("food_id")),
                                    Integer.parseInt(object.getString("price")),
                                    Integer.parseInt(object.getString("unit")),
                                    Integer.parseInt(object.getString("Veg")),
                                    object.getString("food_item_name"),
                                    object.getString("cuisine")));

                                    total += Integer.parseInt(object.getString("price")) * Integer.parseInt(object.getString("unit"));

                            }

                        //Toast.makeText(Cart1.this, ""+total, Toast.LENGTH_SHORT).show();
                        total_amount.setText("\u20b9 "+total);
                        adapter = new CartAdapter(Cart1.this,arrayList,total_amount);
                        cart_items.setAdapter(adapter);
                    }
                    else {
                        Toast.makeText(Cart1.this, "Please add some food to cart...", Toast.LENGTH_SHORT).show();
                        finish();
                    }
                }
                catch (Exception e){
                    Log.d("-----success log-----",e.toString());
                }

            }

            @Override
            public void onFinish() {
                super.onFinish();
                dialog.dismiss();
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONArray errorResponse) {
                super.onFailure(statusCode, headers, throwable, errorResponse);

                Toast.makeText(Cart1.this, ""+errorResponse, Toast.LENGTH_SHORT).show();
                Log.d("---error---",errorResponse.toString());
            }

        });

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

    public void setScreen() {
        actionBar = getSupportActionBar();
        actionBar.setHomeAsUpIndicator(R.drawable.back);
        actionBar.setDisplayHomeAsUpEnabled(true);
        actionBar.setHomeButtonEnabled(true);
        actionBar.setElevation(25);
        actionBar.setTitle("Cart");
        //getWindow().setStatusBarColor(getResources().getColor(R.color.gray));
    }

    public void initView() {
        cart_items = findViewById(R.id.cart_items);
        restaurant_name = findViewById(R.id.restaurant_name);
        total_amount = findViewById(R.id.total_amount);
        check_out = findViewById(R.id.check_out);


        client = new AsyncHttpClient();
        params = new RequestParams();

    }

}
