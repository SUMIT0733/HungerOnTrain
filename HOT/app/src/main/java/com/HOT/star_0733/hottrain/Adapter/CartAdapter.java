package com.HOT.star_0733.hottrain.Adapter;

import android.app.Activity;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.HOT.star_0733.hottrain.CommonUtil;
import com.HOT.star_0733.hottrain.R;
import com.HOT.star_0733.hottrain.model.CartData;

import com.HOT.star_0733.hottrain.model.CartData;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import cc.cloudist.acplibrary.ACProgressConstant;
import cc.cloudist.acplibrary.ACProgressFlower;
import cz.msebera.android.httpclient.Header;

public class CartAdapter extends ArrayAdapter<CartData> {

    int total,unit,base,final_total;
    ArrayList<CartData> arrayList;
    Activity context;
    TextView food_name,food_cuisine,food_price,total_amount;
    ImageView food_type,remove;

    AsyncHttpClient client;
    RequestParams params;
    ACProgressFlower dialog;

    SharedPreferences preferences;
    SharedPreferences.Editor editor;

    public CartAdapter(@NonNull Activity context, @NonNull ArrayList<CartData> list, TextView total_amount) {
        super(context, R.layout.cart_item_row,list);
        this.context = context;
        this.arrayList = list;
        this.total_amount = total_amount;
    }

    @NonNull
    @Override
    public View getView(final int i, @Nullable View convertView, @NonNull ViewGroup parent) {

        final CartData cartData = arrayList.get(i);
        LayoutInflater inflater = context.getLayoutInflater();
        convertView = inflater.inflate(R.layout.cart_item_row,null,true);

        //final_total = 0;

        dialog = new ACProgressFlower.Builder(context)
                .direction(ACProgressConstant.DIRECT_CLOCKWISE)
                .themeColor(R.color.green_700)
                .text("Deleting...")
                .bgColor(Color.WHITE)
                .textAlpha(1)
                .speed(15)
                .fadeColor(Color.WHITE)
                .build();


              client = new AsyncHttpClient();
              params = new RequestParams();

              food_name = convertView.findViewById(R.id.food_name);
              food_cuisine = convertView.findViewById(R.id.food_cuisine);
              food_price = convertView.findViewById(R.id.food_price);
              food_type = convertView.findViewById(R.id.food_type);
              remove = convertView.findViewById(R.id.remove);

              food_name.setText(cartData.getFood_item_name() + " \n* " + cartData.getUnit());
              food_cuisine.setText("in " + cartData.getCuisine());
              base = cartData.getPrice();
              unit = cartData.getUnit();

              total = base * unit;
              food_price.setText("\u20B9 " + total);

              final_total += total;
              //total_amount.setText("\u20b9 "+final_total);

              if (cartData.getVeg() == 1) {
                    food_type.setImageResource(R.drawable.veg);
              } else {
                    food_type.setImageResource(R.drawable.nonveg);
              }

              remove.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                          deleteItem(cartData.getCart_id(), i);
                    }
              });
        return convertView;
    }

    public void deleteItem(int cart_id, final int i) {

        params.put("cart_id",cart_id);

        client.post(CommonUtil.url+"deletecart.php",params,new JsonHttpResponseHandler(){

            @Override
            public void onStart() {
                super.onStart();

                dialog.show();
            }

            @Override
            public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                //super.onSuccess(statusCode, headers, response);

                dialog.dismiss();
                try {
                    String res = response.getString("responce");
                    if(res.equals("remove")){
                       arrayList.remove(i);
                        int temptotal=0;
                        for(int j=0;j<arrayList.size();j++){
                            base = arrayList.get(j).getPrice();
                            unit = arrayList.get(j).getUnit();
                            temptotal=temptotal+base * unit;
                        }
                        total_amount.setText("\u20b9 "+temptotal);
                        if(arrayList.size() > 0)
                              notifyDataSetChanged();
                        else
                              context.finish();

                    }else{
                        Toast.makeText(context, ""+res, Toast.LENGTH_SHORT).show();
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                //super.onFailure(statusCode, headers, throwable, errorResponse);

                dialog.dismiss();
                Toast.makeText(context, ""+errorResponse, Toast.LENGTH_SHORT).show();

            }

            @Override
            public void onFinish() {
                super.onFinish();

                dialog.dismiss();

            }
        });
    }
}
