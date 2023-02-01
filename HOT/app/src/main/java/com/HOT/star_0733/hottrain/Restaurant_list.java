package com.HOT.star_0733.hottrain;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.SearchView;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.HOT.star_0733.hottrain.Adapter.RestaurantListAdapter;

import com.HOT.star_0733.hottrain.R;

import com.HOT.star_0733.hottrain.model.Restaurant_list_model;
import com.facebook.shimmer.ShimmerFrameLayout;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import cz.msebera.android.httpclient.Header;
import fr.castorflex.android.circularprogressbar.CircularProgressBar;

public class Restaurant_list extends AppCompatActivity{

    ShimmerFrameLayout shimmer;
    LinearLayout content;
    ImageView cart;
    ActionBar actionBar;
    Intent intent;
    String names,emails,id,city,tocken,code,station;
    SharedPreferences sharedPreferences;
    public static final String MyPREFERENCES = "HOT";
    ArrayList<Restaurant_list_model> list;
    ListView listView;
    EditText edit;
    RestaurantListAdapter adapter;
    TextView textView_city,header_title,empty_view;
    int SPLASH_TIME_OUT = 500;
    SwipeRefreshLayout swipe;
    ConnectivityManager connectivityManager;
    boolean wifi,data;
    AsyncHttpClient client;
    CircularProgressBar progressBar;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_restaurant_list);

        connectivityManager = ((ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE));
        actionBar = getSupportActionBar();

        initView();
        getIntents();
        setScreen();
        getPref();
        wifi = getWifi();
        data = getData();

          if(!wifi  && !data){
                Toast.makeText(this, "No Internet Connection.", Toast.LENGTH_SHORT).show();
                AlertDialog.Builder builder = new AlertDialog.Builder(Restaurant_list.this);
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
                loadList();
          }


          swipe.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
              @Override
              public void onRefresh() {
                    wifi = getWifi();
                    data = getData();
                    swipe.setRefreshing(false);
                    if(!wifi  && !data){
                          Toast.makeText(Restaurant_list.this, "No Internet Connection", Toast.LENGTH_SHORT).show();
                          AlertDialog.Builder builder = new AlertDialog.Builder(Restaurant_list.this,R.style.Dialog_Theme);
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
                          loadList();
                    }
              }
        });

        //handle click event
        listView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {

                  wifi = getWifi();
                  data = getData();
                  swipe.setRefreshing(false);
                  if(!wifi  && !data){
                        Toast.makeText(Restaurant_list.this, "No Internet Connection", Toast.LENGTH_SHORT).show();
                        AlertDialog.Builder builder = new AlertDialog.Builder(Restaurant_list.this,R.style.Dialog_Theme);
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
                        startActivity(new Intent(Restaurant_list.this,Section_Menu.class)
                                    .putExtra("data",list.get(i)));
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

      public void loadList() {
            list = new ArrayList<>();
            list.clear();
            RequestParams params = new RequestParams();
            params.put("id",code);
            Log.d("---doce---",code);
            client.post(CommonUtil.url+"getrestaurant.php",params,new JsonHttpResponseHandler(){
                  @Override
                  public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                        super.onSuccess(statusCode, headers, response);

                        try {
                              String responce = response.getString("responce");
                              if(responce.equals("Success")){
                                    empty_view.setVisibility(View.GONE);
                                    list.clear();
                                    Log.d("---list---",response.toString());
                                    JSONArray data = response.getJSONArray("data");
                                    for(int i=0;i<data.length();i++){
                                          JSONObject object = data.getJSONObject(i);
                                          list.add(new Restaurant_list_model(Integer.parseInt(object.getString("restaurant_id")),
                                                      object.getString("restaurant_pincode"),
                                                      object.getString("restaurant_name"),
                                                      object.getString("email"),
                                                      object.getString("restaurant_address"),
                                                      object.getString("contact_no"),
                                                      object.getString("website"),
                                                      object.getString("profile_url"),
                                                      object.getString("cuisine"),
                                                      Float.parseFloat(object.getString("rest_rating")),
                                                      object.getString("city"),
                                                      object.getString("state")));
                                    }
                              }
                              else if(responce.equals("No data")){
                                    Toast.makeText(Restaurant_list.this, "No restaurant found in this city.", Toast.LENGTH_SHORT).show();
                              }else {
                              }
                              adapter  = new RestaurantListAdapter(Restaurant_list.this,list);
                              listView.setAdapter(adapter);

                        } catch (JSONException e) {
                              e.printStackTrace();
                        }
                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                        super.onFailure(statusCode, headers, throwable, errorResponse);

                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                        super.onFailure(statusCode, headers, responseString, throwable);

                  }

                  @Override
                  public void onStart() {
                        super.onStart();
                        shimmer.startShimmer();

                  }

                  @Override
                  public void onFinish() {
                        super.onFinish();
                        shimmer.stopShimmer();
                        shimmer.setVisibility(View.GONE);
                  }
            });

      }

      @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.cart,menu);

            MenuItem mSearch = menu.findItem(R.id.search);
            SearchView mSearchView = (SearchView) mSearch.getActionView();
            mSearchView.setQueryHint("Type a restaurant name");

            mSearchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {

                  @Override
                  public boolean onQueryTextSubmit(String query) {
                        return false;
                  }

                  @Override
                  public boolean onQueryTextChange(String newText) {
                        adapter.filter(newText);
                        return true;
                  }
            });
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()){
              case android.R.id.home:
                    onBackPressed();
                    return true;
            case R.id.cart:
                startActivity(new Intent(Restaurant_list.this,Cart1.class));
                return true;
        }
        return true;
    }

      public void getPref() {
            sharedPreferences = getSharedPreferences(MyPREFERENCES, Context.MODE_PRIVATE);

            names = sharedPreferences.getString("name", "john");
            emails = sharedPreferences.getString("email", "abcd@abc.com");
            id = sharedPreferences.getString("id", "100");
    }

      public void getIntents() {
            Intent intent = getIntent();
            city = intent.getStringExtra("city");
            code = intent.getStringExtra("code");
            station = intent.getStringExtra("station");

            if(city.equals(null)){
                  finish();
            }
            else{
                  header_title.setText(""+city);
            }
    }

      public void setScreen() {
            actionBar.setElevation(35);
            actionBar.setTitle("Restaurant");
            actionBar.setDisplayHomeAsUpEnabled(true);
            actionBar.setHomeButtonEnabled(true);


      }

      public void initView() {

          client = new AsyncHttpClient();
          swipe = findViewById(R.id.swipe);
          swipe.setColorSchemeColors(getResources().getColor(R.color.red_100),
                      getResources().getColor(R.color.red_300),
                      getResources().getColor(R.color.red_500),
                      getResources().getColor(R.color.red_700));
            shimmer = findViewById(R.id.shimmer);
//
//
//            new Handler().postDelayed(new Runnable() {
//                  @Override
//                  public void run() {
//                        shimmer.stopShimmer();
//                        shimmer.setVisibility(View.GONE);
//                  }
//            }, SPLASH_TIME_OUT);
            listView = findViewById(R.id.list);
            header_title = findViewById(R.id.header_title);
            empty_view = findViewById(R.id.emptyView);

      }

      @Override
        public void onBackPressed() {
            AlertDialog.Builder builder = new AlertDialog.Builder(Restaurant_list.this,R.style.Dialog_Theme);
                        builder.setMessage("Once you left the Restaurant page, your cart will be empty")
                        .setPositiveButton("ok", new DialogInterface.OnClickListener() {
                              @Override
                              public void onClick(DialogInterface dialogInterface, int i) {
                                    deleteCart(id);
                                    finish();
                              }
                        })
                        .setNegativeButton("cancel", new DialogInterface.OnClickListener() {
                              @Override
                              public void onClick(DialogInterface dialogInterface, int i) {
                                    dialogInterface.dismiss();
                              }
                        })
                        .show();
        }

      public void deleteCart(String id) {
            RequestParams params = new RequestParams();
            params.put("id",id);
            client.post(CommonUtil.url+"deletecarts.php",params,new JsonHttpResponseHandler(){
                  @Override
                  public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                        super.onSuccess(statusCode, headers, response);
                        finish();
                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                        super.onFailure(statusCode, headers, throwable, errorResponse);
                        finish();
                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                        super.onFailure(statusCode, headers, responseString, throwable);
                        finish();
                  }

                  @Override
                  public void onStart() {
                        super.onStart();

                  }

                  @Override
                  public void onFinish() {
                        super.onFinish();
                        finish();
                  }
            });
      }
}

