package com.HOT.star_0733.hottrain;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Color;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.support.design.widget.FloatingActionButton;
import android.support.v4.app.DialogFragment;
import android.support.v4.app.FragmentTransaction;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AbsListView;
import android.widget.AdapterView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;
import com.HOT.star_0733.hottrain.Adapter.MenuItemAdapter;
import com.HOT.star_0733.hottrain.dialog.MenuInfoDialog;
import com.HOT.star_0733.hottrain.dialog.Restaurant_info;
import com.HOT.star_0733.hottrain.model.Menu_item;
import com.HOT.star_0733.hottrain.model.Restaurant_list_model;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;
import org.json.JSONArray;
import org.json.JSONObject;
import java.util.ArrayList;
import cc.cloudist.acplibrary.ACProgressConstant;
import cc.cloudist.acplibrary.ACProgressFlower;
import cz.msebera.android.httpclient.Header;

public class Section_Menu extends AppCompatActivity {

    ListView listView;
    TextView rest_name,cuisine,rest_name_def,cuisine_def;
    ArrayList<Menu_item> arrayList;
    MenuItemAdapter menuItemAdapter;
    Intent intent;
    int id;
    FloatingActionButton cart;
    String str_name,str_cuisine,str_time="";
    AsyncHttpClient client;
    ProgressDialog pd;
    ImageView info,info_def;
      ConnectivityManager connectivityManager;
      boolean wifi,data;
      SwipeRefreshLayout swipe;
    ACProgressFlower dialog;
      View header,footer;
      LinearLayout header_linear;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_section_menu);

          connectivityManager = ((ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE));
          intent = getIntent();

          header = getLayoutInflater().inflate(R.layout.menu_header,null);
          footer = getLayoutInflater().inflate(R.layout.space,null);

          initView();
        setScreen();


        final Restaurant_list_model model = intent.getParcelableExtra("data");

        id = model.getRestaurant_id();
        rest_name.setText(model.getRestaurant_name());
        if(model.getCuisine().equals("false")){
              cuisine.setText(" ");
        }else{
              cuisine.setText(model.getCuisine());
        }
      str_time = "Delivery in 30-40 minutes Free Delivery.";

      rest_name_def.setText(model.getRestaurant_name());
          if(model.getCuisine().equals("false")){
                cuisine_def.setText(" ");
          }else{
                cuisine_def.setText(model.getCuisine());
          }
      info_def.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                  FragmentTransaction ft = getSupportFragmentManager().beginTransaction();
                  DialogFragment dia = new Restaurant_info();
                  Bundle bundle = new Bundle();
                  bundle.putParcelable("data",model);
                  dia.setCancelable(true);
                  dia.setArguments(bundle);
                  dia.show(ft,"restaurant_info");
            }
      });

          wifi = getWifi();
          data = getData();

          if(!wifi  && !data){
                Toast.makeText(this, "No Internet Connection.", Toast.LENGTH_SHORT).show();
                AlertDialog.Builder builder = new AlertDialog.Builder(Section_Menu.this);
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
                showlist();
          }

          swipe.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
                @Override
                public void onRefresh() {
                      wifi = getWifi();
                      data = getData();
                        swipe.setRefreshing(false);
                      if(!wifi  && !data){
                            Toast.makeText(Section_Menu.this, "No Internet Connection.", Toast.LENGTH_SHORT).show();
                            AlertDialog.Builder builder = new AlertDialog.Builder(Section_Menu.this);
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
                            showlist();
                      }
                }
          });

        info.setOnClickListener(new View.OnClickListener() {
              @Override
              public void onClick(View view) {
                    showDialogInfo();
              }
        });
        listView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                  if(i == (arrayList.size()+1)){}
                  else if(i == 0){
                        FragmentTransaction ft = getSupportFragmentManager().beginTransaction();
                        DialogFragment dia = new Restaurant_info();
                        Bundle bundle = new Bundle();
                        bundle.putParcelable("data",model);
                        dia.setCancelable(true);
                        dia.setArguments(bundle);
                        dia.show(ft,"restaurant_info");
                  }
                  else {
                        wifi = getWifi();
                        data = getData();

                        if(!wifi  && !data){
                              Toast.makeText(Section_Menu.this, "No Internet Connection.", Toast.LENGTH_SHORT).show();
                              AlertDialog.Builder builder = new AlertDialog.Builder(Section_Menu.this);
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
                              FragmentTransaction ft = getSupportFragmentManager().beginTransaction();
                              DialogFragment dia = new MenuInfoDialog();
                              Bundle bundle = new Bundle();
                              Menu_item item = arrayList.get(i-1);
                              bundle.putSerializable("item",item);
                              dia.setCancelable(false);
                              dia.setArguments(bundle);
                              dia.show(ft,"menu_info");
                        }
                  }

            }
        });

        cart.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                  wifi = getWifi();
                  data = getData();

                  if(!wifi  && !data){
                        Toast.makeText(Section_Menu.this, "No Internet Connection.", Toast.LENGTH_SHORT).show();
                        AlertDialog.Builder builder = new AlertDialog.Builder(Section_Menu.this);
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
                        startActivity(new Intent(Section_Menu.this,Cart1.class));
                  }
            }
        });

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

      private void initView() {
            dialog = new ACProgressFlower.Builder(Section_Menu.this)
                        .direction(ACProgressConstant.DIRECT_CLOCKWISE)
                        .themeColor(R.color.grey_700)
                        .text("Loading...")
                        .textColor(Color.BLACK)
                        .bgColor(Color.WHITE)
                        .textAlpha(1)
                        .speed(15)
                        .bgAlpha(1)
                        .fadeColor(Color.WHITE)
                        .build();

            cart = findViewById(R.id.cart);
            swipe = findViewById(R.id.swipe);
            info = header.findViewById(R.id.info);
            rest_name = header.findViewById(R.id.rest_name);
            cuisine = header.findViewById(R.id.cuisine);
            client = new AsyncHttpClient();
            pd = new ProgressDialog(Section_Menu.this);

            listView = findViewById(R.id.list);
            listView.addHeaderView(header);
            listView.addFooterView(footer);
            arrayList = new ArrayList<>();
            header_linear = findViewById(R.id.header_linear);
            rest_name_def = findViewById(R.id.rest_name_def);
            cuisine_def = findViewById(R.id.cuisine_def);
            info_def = findViewById(R.id.info_def);
      }

      private void setScreen() {
            getSupportActionBar().hide();
            getWindow().setStatusBarColor(Color.parseColor("#ff000000"));
    }

      private void showDialogInfo() {

      }

      public void showlist() {
        RequestParams params = new RequestParams();
        params.put("id",id);
        arrayList.clear();
        client.post(CommonUtil.url+"menu_list.php",params,new JsonHttpResponseHandler()
        {
            @Override
            public void onStart() {
                dialog.show();
            }

            @Override
            public void onFinish() {
                super.onFinish();
                dialog.dismiss();
            }

            @Override
            public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                try{
                    Log.d("-----response---",response.getString("responce"));
                    String res = response.getString("responce");

                    if(res.equals("Success")){
                        JSONArray array = response.getJSONArray("menu");

                              for (int i = 0; i < array.length(); i++) {
                                    JSONObject object = array.getJSONObject(i);

                                    arrayList.add(new Menu_item(object.getInt("food_item_id"),
                                                id,
                                                object.getString("cuisine_name"),
                                                Integer.parseInt(object.getString("food_item_price")),
                                                Integer.parseInt(object.getString("Veg")),
                                                object.getString("food_item_name"),
                                                object.getString("ingredients"))
                                    );

                              }
                          menuItemAdapter = new MenuItemAdapter(Section_Menu.this, arrayList);
                          listView.setAdapter(menuItemAdapter);
                        }else{
                          Toast.makeText(Section_Menu.this, res, Toast.LENGTH_SHORT).show();
                          header_linear.setVisibility(View.VISIBLE);
                    }

                }
                catch (Exception e){
                        Log.d("-----success log-----",e.toString());
                        finish();

                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                Log.d("----fail----",responseString);
                Toast.makeText(Section_Menu.this, "Error occured.. Try after sometime.. outside\n"+responseString, Toast.LENGTH_SHORT).show();
            }
        });
    }
}
