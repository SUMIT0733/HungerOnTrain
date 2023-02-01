package com.HOT.star_0733.hottrain.dialog;

import android.content.Context;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.AppCompatButton;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageButton;
import android.widget.TextView;
import android.widget.Toast;
import com.HOT.star_0733.hottrain.CommonUtil;
import com.HOT.star_0733.hottrain.R;
import com.HOT.star_0733.hottrain.model.Menu_item;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;
import org.json.JSONException;
import org.json.JSONObject;
import cc.cloudist.acplibrary.ACProgressConstant;
import cc.cloudist.acplibrary.ACProgressFlower;
import cz.msebera.android.httpclient.Header;
import me.himanshusoni.quantityview.QuantityView;

public class MenuInfoDialog extends android.support.v4.app.DialogFragment {
    View view;
    ImageButton button;
    Bundle bundle;
    TextView name,cuisine,detail,quantity,cancel,add;
    AppCompatButton price;
    QuantityView unit;
    Menu_item list;
    AsyncHttpClient client;
    ACProgressFlower dialog;
    SharedPreferences preferences;
    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, Bundle savedInstanceState) {
        view = inflater.inflate(R.layout.menu_info,null,true);
        bundle = getArguments();
        list = (Menu_item) bundle.getSerializable("item");
        getDialog().getWindow().setBackgroundDrawable(getResources().getDrawable(R.drawable.layout_border));

        preferences = getActivity().getSharedPreferences("HOT",Context.MODE_PRIVATE);

        dialog = new ACProgressFlower.Builder(getActivity())
                .direction(ACProgressConstant.DIRECT_CLOCKWISE)
                .themeColor(R.color.grey_700)
                .text("Adding to cart...")
                .textColor(Color.BLACK)
                .bgColor(Color.WHITE)
                .textAlpha(1)
                .speed(15)
                .bgAlpha(1)
                .fadeColor(Color.WHITE)
                .build();

        //Toast.makeText(getActivity(), ""+list.getName(), Toast.LENGTH_SHORT).show();
        initView();
        setValue();
        return view;
    }

    public void setValue() {
        name.setText(list.getName());
        cuisine.setText("in "+list.getCuisine());
        if(list.getIngerdients().equals("null")){
              detail.setText(" ");
        }else {
              detail.setText(list.getIngerdients());
        }
        quantity.setText("\u20b9 "+list.getPrice());
        price.setText("\u20b9 "+list.getPrice());
    }

    public void initView() {
        button = view.findViewById(R.id.bt_close);
        name = view.findViewById(R.id.name);
        price = view.findViewById(R.id.price);
        detail = view.findViewById(R.id.detail);
        quantity = view.findViewById(R.id.quantity);
        cancel = view.findViewById(R.id.cancel);
        cuisine = view.findViewById(R.id.cuisine);
        add = view.findViewById(R.id.add);
        unit = view.findViewById(R.id.unit);

        client = new AsyncHttpClient();
    }

    @Override
    public void onViewCreated(View view, @Nullable Bundle savedInstanceState) {
        button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getDialog().dismiss();
            }
        });

        cancel.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getDialog().dismiss();
            }
        });

        unit.setOnQuantityChangeListener(new QuantityView.OnQuantityChangeListener() {
            @Override
            public void onQuantityChanged(int oldQuantity, int newQuantity, boolean programmatically) {
                int newprice = list.getPrice() * newQuantity;
                price.setText("\u20b9 "+newprice);
            }

            @Override
            public void onLimitReached() {

            }
        });

        add.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
//                Toast.makeText(getActivity(), "name:- "+name.getText()+
//                        "\nCuisine:- "+cuisine.getText()+
//                        "\nDetail:- "+detail.getText()+
//                        "\nPrice:- "+price.getText()+
//                        "\nUnit:- "+unit.getQuantity()+
//                        "\nId:- "+list.getFood_id()+
//                        "\nResi_id:- "+list.getRest_id(),Toast.LENGTH_LONG).show();
                //Toast.makeText(getActivity(), ""+list.getCuisine(), Toast.LENGTH_SHORT).show();
                addToCart();

            }
        });

    }

    public void addToCart() {
        RequestParams params = new RequestParams();
        params.put("rest_id",list.getRest_id());
        params.put("food_id",list.getFood_id());
        params.put("cuisine",list.getCuisine());
        //params.put("name",list.getName());
        params.put("price",list.getPrice());
        params.put("unit",unit.getQuantity());
        params.put("user_id",preferences.getString("id", "100"));

        client.post(CommonUtil.url+"addcart.php",params,new JsonHttpResponseHandler(){
            @Override
            public void onStart() {
                super.onStart();
                dialog.show();
            }

            @Override
            public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                getDialog().dismiss();
                try {
                    String responce = response.getString("responce");
                    Log.d("---res---add",responce);
                    switch (responce){
                        case "Update success":
                            Toast.makeText(getActivity(), "Add to cart successfully", Toast.LENGTH_SHORT).show();
                            break;
                        case "error in update":
                            Toast.makeText(getActivity(), "Error in edit cart", Toast.LENGTH_SHORT).show();
                            break;
                        case "Add successfully":
                            Toast.makeText(getActivity(), "Add to cart successfully", Toast.LENGTH_SHORT).show();
                            break;
                        case "error in add":
                            Toast.makeText(getActivity(), "Error in edit cart", Toast.LENGTH_SHORT).show();
                            break;
                        case "No Food From Other Restaurant Allowed":
                            Toast.makeText(getActivity(), "Sorry! You can order From only one restaurant at a time.", Toast.LENGTH_SHORT).show();
                            break;


                    }
                } catch (JSONException e) {
                    Toast.makeText(getActivity(), ""+e.getMessage(), Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                getDialog().dismiss();
                Toast.makeText(getActivity(), ""+responseString, Toast.LENGTH_SHORT).show();

            }

            @Override
            public void onFinish() {
                super.onFinish();
                dialog.dismiss();
                getDialog().dismiss();

            }
        });

    }
}
