package com.HOT.star_0733.hottrain;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.view.inputmethod.InputMethodManager;
import android.widget.AdapterView;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.HOT.star_0733.hottrain.Adapter.Train_row_list_adapter;

import com.HOT.star_0733.hottrain.Adapter.Train_row_list_adapter;
import com.HOT.star_0733.hottrain.R;

import com.HOT.star_0733.hottrain.model.Train_list_model;

import com.HOT.star_0733.hottrain.model.Train_list_model;
import com.google.android.gms.auth.api.signin.GoogleSignIn;
import com.google.android.gms.auth.api.signin.GoogleSignInClient;
import com.google.android.gms.auth.api.signin.GoogleSignInOptions;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.io.InputStream;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;
import java.util.TimeZone;

import cc.cloudist.acplibrary.ACProgressConstant;
import cc.cloudist.acplibrary.ACProgressFlower;
import cz.msebera.android.httpclient.Header;
import de.hdodenhof.circleimageview.CircleImageView;
import fr.castorflex.android.circularprogressbar.CircularProgressBar;

public class Select_City extends AppCompatActivity{

    ActionBar actionBar;
    GoogleSignInClient mGoogleSignInClient;
    FirebaseAuth auth;
    LinearLayout list_header;
    EditText train_number;
    CircleImageView get_train;
    TextView train_name,status;
    ListView list;
    View header_line;
    CircularProgressBar progressBar;
    String train_number_str;
    String tmp_arrival,tmp_depart,arrival,depart,station_name,code;
    Train_row_list_adapter adapter;
    ArrayList<Train_list_model> arrayList;
    AsyncHttpClient client;
    ACProgressFlower dialog;
    ConnectivityManager connectivityManager;
    boolean wifi,data;
    SharedPreferences preferences;
    SharedPreferences.Editor editor;
    String train_time,current_time;
      String names,emails,id;

    Calendar c;
      SharedPreferences sharedPreferences;
      public static final String MyPREFERENCES = "HOT";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_select_city);

          connectivityManager = ((ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE));
          actionBar = getSupportActionBar();

        setScreen();
        initView();
        getPref();
          wifi = getWifi();
          data = getData();

            c = Calendar.getInstance(Locale.getDefault());
          Date currentLocalTime = c.getTime();
          DateFormat date = new SimpleDateFormat("HH:mm");
          date.setTimeZone(TimeZone.getDefault());
          current_time = date.format(currentLocalTime);

          if(!wifi  && !data){
                Toast.makeText(this, "No Internet Connection.", Toast.LENGTH_SHORT).show();
                android.support.v7.app.AlertDialog.Builder builder = new android.support.v7.app.AlertDialog.Builder(Select_City.this);
                builder.setTitle("Error")
                            .setMessage("No internet connection.Try again.")
                            .setCancelable(false)
                            .setNegativeButton("cancel", new DialogInterface.OnClickListener() {
                                  @Override
                                  public void onClick(DialogInterface dialogInterface, int i) {
                                        dialogInterface.dismiss();
                                        finish();
                                  }
                            }).show();
          }else {
                loadJSONFromAsset("karnavati.json");
          }

        get_train.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                  wifi = getWifi();
                  data = getData();
                  if(!wifi  && !data){
                        Toast.makeText(Select_City.this, "No Internet Connection", Toast.LENGTH_SHORT).show();
                        android.support.v7.app.AlertDialog.Builder builder = new android.support.v7.app.AlertDialog.Builder(Select_City.this,R.style.Dialog_Theme);
                        builder.setTitle("Error")
                                    .setMessage("No internet connection.Try again.")
                                    .setCancelable(false)
                                    .setNegativeButton("ok", new DialogInterface.OnClickListener() {
                                          @Override
                                          public void onClick(DialogInterface dialogInterface, int i) {
                                                dialogInterface.dismiss();
                                                finish();
                                          }
                                    }).show();
                  }else {

                        hideSoftKeyboard(Select_City.this, view);
                        arrayList.clear();
                        list_header.setVisibility(View.GONE);
                        header_line.setVisibility(View.GONE);
                        train_name.setText("Train name");
                        train_number_str = train_number.getText().toString().trim();
                        if(train_number_str == null || train_number_str.length() < 4) {
                              Toast.makeText(Select_City.this, "Invalid train number.", Toast.LENGTH_SHORT).show();
                        }
                        else {
                              if(train_number_str.equals("19033")){
                                    loadJSONFromAsset("queen.json");
                              }else if (train_number_str.equals("12933")){
                                    loadJSONFromAsset("karnavati.json");
                              }else {
                                    Toast.makeText(Select_City.this, "Invalid train number.", Toast.LENGTH_SHORT).show();
                              }
                              //loadList();
                             // getTrainInfo();
                        }
                  }
            }
        });

        list.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
                Train_list_model model = arrayList.get(i);

                  wifi = getWifi();
                  data = getData();

                  if(!wifi  && !data){
                        Toast.makeText(Select_City.this, "No Internet Connection.", Toast.LENGTH_SHORT).show();
                        android.support.v7.app.AlertDialog.Builder builder = new android.support.v7.app.AlertDialog.Builder(Select_City.this);
                        builder.setTitle("Error")
                                    .setMessage("No internet connection.Try again.")
                                    .setCancelable(false)
                                    .setNegativeButton("cancel", new DialogInterface.OnClickListener() {
                                          @Override
                                          public void onClick(DialogInterface dialogInterface, int i) {
                                                dialogInterface.dismiss();
                                                finish();
                                          }
                                    }).show();
                  }else {
                        if(model.getName().equals(" ")){
                              Toast.makeText(Select_City.this, "Select Proper station.", Toast.LENGTH_SHORT).show();
                        }else {
                              String station = model.getName();
                              String[] arr = station.split(" ");
                              String result = "";
                              if (arr.length > 0) {
                                    result = station.substring(0, station.lastIndexOf(" " + arr[arr.length-1]));
                              }
                             // Toast.makeText(Select_City.this, ""+current_time+" "+model.getArrival_time(), Toast.LENGTH_SHORT).show();
                              if((toMinute(model.getArrival_time()) - toMinute(current_time) > 60))
                                    getCity(result,model.getArrival_time());
                              else
                                    //getCity(result,model.getArrival_time());
                              Toast.makeText(Select_City.this, "You have to place order atleast 1 hour before arriving at the station.", Toast.LENGTH_SHORT).show();
                        }
                  }

            }
        });

        deleteCart(id);
    }


      public void deleteCart(String id) {
          RequestParams params = new RequestParams();
          params.put("id",id);
          client.post(CommonUtil.url+"deletecarts.php",params,new JsonHttpResponseHandler(){
                @Override
                public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                      super.onSuccess(statusCode, headers, response);
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

                }

                @Override
                public void onFinish() {
                      super.onFinish();
                }
          });
      }

      @Override
      protected void onRestart() {
            super.onRestart();
            getPref();
            deleteCart(id);
      }

      public void getPref() {
            sharedPreferences = getSharedPreferences(MyPREFERENCES, Context.MODE_PRIVATE);

            names = sharedPreferences.getString("name", "john");
            emails = sharedPreferences.getString("email", "abcd@abc.com");
            id = sharedPreferences.getString("id", "100");
      }


      public int toMinute(String time){

          int int_time = Integer.parseInt(time.split(":")[0])*60 + Integer.parseInt(time.split(":")[1]);
          return int_time;
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

    public void getCity(final String result, final String arrival_time) {
        RequestParams params = new RequestParams();
        params.put("station",result);

        client.post(CommonUtil.url+"getcity.php",params,new JsonHttpResponseHandler(){
            @Override
            public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                super.onSuccess(statusCode, headers, response);
                dialog.dismiss();
                try {
                    String responce = response.getString("responce");
                    if(responce.equals("Success")){
                        String city = response.getString("city_name");
                        String code = response.getString("city_id");
                        showAlert(city,code,result,arrival_time);
                    }
                    else if (responce.equals("Error")){
                        showAlertError();
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                super.onFailure(statusCode, headers, throwable, errorResponse);
                dialog.dismiss();
                Toast.makeText(Select_City.this, "Error occured.", Toast.LENGTH_SHORT).show();

            }

            @Override
            public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                super.onFailure(statusCode, headers, responseString, throwable);
                Toast.makeText(Select_City.this, " "+responseString, Toast.LENGTH_SHORT).show();
                dialog.dismiss();
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

    public void showAlertError() {
        AlertDialog.Builder builder = new AlertDialog.Builder(Select_City.this,R.style.Dialog_Theme);
        builder.setTitle("Information")
                .setMessage("We don't deliver food at this station.")
                .setPositiveButton("Ok", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialogInterface, int i) {
                        dialogInterface.dismiss();
                    }
                })
                .create();
        builder.show();
    }

    public void showAlert(final String city, final String code, final String result, final String arrival_time) {
        AlertDialog.Builder builder = new AlertDialog.Builder(Select_City.this,R.style.Dialog_Theme);
        builder.setTitle("Confirmation")
                .setMessage("You will get the food from "+city+" .")
                .setPositiveButton("Proceed", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialogInterface, int i) {
                       startActivity(new Intent(Select_City.this,Restaurant_list.class)
                       .putExtra("city",city)
                       .putExtra("code",code)
                       .putExtra("station",result));
                       editor.putString("station",result);
                       editor.putString("city",city);
                       editor.putString("time",arrival_time);
                       editor.commit();
                    }
                })
                .setNegativeButton("cancel", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialogInterface, int i) {
                        dialogInterface.dismiss();
                    }
                }).create();
        builder.show();
    }


    public void loadJSONFromAsset(String file_name) {
        String json = null;
        try {
            InputStream is = getAssets().open(file_name);
            int size = is.available();
            byte[] buffer = new byte[size];
            is.read(buffer);
            is.close();
            json = new String(buffer, "UTF-8");
            loadListJson(json);
        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }

    public void loadListJson(String json_string) {
//        list_header.setVisibility(View.VISIBLE);
//        header_line.setVisibility(View.VISIBLE);
        progressBar.setVisibility(View.GONE);
        //Toast.makeText(Select_City.this, response.toString(), Toast.LENGTH_SHORT).show();

        try {
            JSONObject response = new JSONObject(json_string);
            JSONObject train = response.getJSONObject("train");
            train_name.setText(train.getString("name")+"  ( "+train.getString("number")+" )");
              editor.putString("train_name",train.getString("name")+"  ( "+train.getString("number")+" )");
              editor.commit();
            JSONArray route = response.getJSONArray("route");
            arrayList.clear();
            for(int i=0;i<route.length();i++){
                JSONObject station_list = route.getJSONObject(i);
                tmp_arrival = station_list.getString("scharr");
                tmp_depart = station_list.getString("schdep");
                if(tmp_arrival.equals("SOURCE")){
                    arrival = tmp_depart;
                    depart = tmp_depart;
                }
                else if(tmp_depart.equals("DEST")){
                    depart = tmp_arrival;
                    arrival = tmp_arrival;
                }
                else {
                    arrival = tmp_arrival;
                    depart = tmp_depart;
                }

                JSONObject station = station_list.getJSONObject("station");
                station_name = station.getString("name");
                code = station.getString("code");

                if (i != 0 || i != (route.length())){
                    arrayList.add(new Train_list_model(" "," "," "));
                }
                arrayList.add(new Train_list_model(station_name+" "+code,arrival,depart));
                if(i == (route.length()-1)){
                    arrayList.add(new Train_list_model(" "," "," "));
                }

            }

            adapter = new Train_row_list_adapter(Select_City.this,arrayList);
            list.setAdapter(adapter);

        } catch (JSONException e) {
            e.printStackTrace();
        }
    }


    public void initView()
    {
        GoogleSignInOptions gso = new GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
                .requestIdToken(getString(R.string.default_web_client_id))
                .requestEmail()
                .build();
        auth = FirebaseAuth.getInstance();
        FirebaseUser user = auth.getCurrentUser();
        mGoogleSignInClient = GoogleSignIn.getClient(Select_City.this, gso);

        preferences = getSharedPreferences("train",Context.MODE_PRIVATE);
        editor = preferences.edit();
        progressBar = findViewById(R.id.progress);
        train_number = findViewById(R.id.train_number);
        train_name = findViewById(R.id.train_name);
        get_train = findViewById(R.id.get_train);
        //status = findViewById(R.id.status);
        list_header = findViewById(R.id.list_header);
        list = findViewById(R.id.list);
        header_line = findViewById(R.id.header_line);
        arrayList = new ArrayList<>();
        client = new AsyncHttpClient();

        dialog = new ACProgressFlower.Builder(Select_City.this)
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


    }

    public void setScreen() {
        actionBar.setElevation(25);
        actionBar.setTitle("Select City");
        actionBar.setDisplayHomeAsUpEnabled(true);
        actionBar.setHomeButtonEnabled(true);
        actionBar.setDisplayOptions(ActionBar.DISPLAY_SHOW_CUSTOM);
        actionBar.setCustomView(R.layout.center_title);
    }

    public void getTrainInfo() {

        client.get("https://api.railwayapi.com/v2/route/train/"+train_number_str+"/apikey/"+CommonUtil.Train_API+"/",new JsonHttpResponseHandler(){
            @Override
            public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                super.onSuccess(statusCode, headers, response);

                list_header.setVisibility(View.VISIBLE);
                header_line.setVisibility(View.VISIBLE);
                progressBar.setVisibility(View.GONE);
                //Toast.makeText(Select_City.this, response.toString(), Toast.LENGTH_SHORT).show();
                Log.d("----trainNumber----",response.toString());

                try {
                    JSONObject train = response.getJSONObject("train");
                    train_name.setText(train.getString("name")+"  ( "+train.getString("number")+" )");
                    editor.putString("train_name",train.getString("name")+"  ( "+train.getString("number")+" )");
                    editor.commit();
                    JSONArray route = response.getJSONArray("route");
                    arrayList.clear();
                    for(int i=0;i<route.length();i++){
                        JSONObject station_list = route.getJSONObject(i);
                        tmp_arrival = station_list.getString("scharr");
                        tmp_depart = station_list.getString("schdep");
                        if(tmp_arrival.equals("SOURCE")){
                            arrival = tmp_depart;
                            depart = tmp_depart;
                        }
                        else if(tmp_depart.equals("DEST")){
                            depart = tmp_arrival;
                            arrival = tmp_arrival;
                        }
                        else {
                            arrival = tmp_arrival;
                            depart = tmp_depart;
                        }

                        JSONObject station = station_list.getJSONObject("station");
                        station_name = station.getString("name");
                        code = station.getString("code");

                          if (i != 0 || i != (route.length())){
                                arrayList.add(new Train_list_model(" "," "," "));
                          }
                          arrayList.add(new Train_list_model(station_name+" "+code,arrival,depart));
                          if(i == (route.length()-1)){
                                arrayList.add(new Train_list_model(" "," "," "));
                          }
//                        arrayList.add(new Train_list_model(station_name+" "+code,arrival,depart));
                    }

                    adapter = new Train_row_list_adapter(Select_City.this,arrayList);
                    list.setAdapter(adapter);

                } catch (JSONException e) {
                    e.printStackTrace();
                }


            }

            @Override
            public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                super.onFailure(statusCode, headers, responseString, throwable);
                progressBar.setVisibility(View.GONE);
                Toast.makeText(Select_City.this, "Some Error occured.Try after sometime!", Toast.LENGTH_SHORT).show();
                Log.d("----error----",responseString);
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                super.onFailure(statusCode, headers, throwable, errorResponse);
                progressBar.setVisibility(View.GONE);
                Toast.makeText(Select_City.this, "Some Error occured.Try after sometime.", Toast.LENGTH_SHORT).show();
                //Log.d("----error----",errorResponse);
            }

            @Override
            public void onStart() {
                super.onStart();
                progressBar.setVisibility(View.VISIBLE);
            }

            @Override
            public void onFinish() {
                super.onFinish();
                progressBar.setVisibility(View.GONE);
            }
        });
    }

    public static void hideSoftKeyboard (Activity activity, View view)
    {
        InputMethodManager imm = (InputMethodManager)activity.getSystemService(Context.INPUT_METHOD_SERVICE);
        imm.hideSoftInputFromWindow(view.getApplicationWindowToken(), 0);
    }

}

//public class Select_City extends AppCompatActivity {
//
//    ActionBar actionBar;
//    Spinner type;
//    EditText text;
//    TextView status;
//    AsyncHttpClient client;
//    ProgressDialog pd;
//    String pnr_detail;
//    Button home;
//    ListView list;
//    ArrayList<City> station_list;
//    GoogleSignInClient mGoogleSignInClient;
//    FirebaseAuth auth;
//    Button check;
//    StationListAdapter adapter;
//    CityAdapter cityAdapter;
//
//    @Override
//    protected void onCreate(Bundle savedInstanceState) {
//        super.onCreate(savedInstanceState);
//        setContentView(R.layout.activity_select_city);
//
//        actionBar = getSupportActionBar();
//
//        setScreen();
//        initView();
//
//        type = findViewById(R.id.type);
//        text = findViewById(R.id.text);
//        list = findViewById(R.id.list);
//        check = findViewById(R.id.check);
//        status = findViewById(R.id.status);
//
//
//        pd = new ProgressDialog(Select_City.this);
//        client = new AsyncHttpClient();
//        station_list = new ArrayList<>();
//        station_list.clear();
//        JSONObject obj;
//
//        try {
//            obj = new JSONObject(loadJSONFromAsset());
//            JSONArray jsonArray = obj.getJSONArray("city");
//            for (int j=0;j<jsonArray.length();j++){
//                JSONObject object = jsonArray.getJSONObject(j);
//                station_list.add(new City(object.getString("city_name"),object.getString("state_name"),object.getInt("city_id"),object.getInt("state_id")));
//            }
//            cityAdapter = new CityAdapter(Select_City.this,station_list);
//            list.setAdapter(cityAdapter);
//        } catch (JSONException e) {
//            e.printStackTrace();
//        }
//
//        type.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
//            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
//                String select = adapterView.getItemAtPosition(i).toString();
//                if(select.equals("PNR No.")){
//                    list.setVisibility(View.GONE);
//                    check.setVisibility(View.VISIBLE);
//                    text.setHint("Enter the PNR number");
//                    check_pnr();
//                }
//                else if(select.equals("Train No.")){
//                    text.setHint("Enter the Train number");
//                }
//                else if(select.equals("Station Name")){
//                    check.setVisibility(View.GONE);
//                    text.setHint("Enter the Station name");
//                    find_station();
//                }
//            }
//            @Override
//            public void onNothingSelected(AdapterView<?> adapterView) { }
//        });
//
//        list.setOnItemClickListener(new AdapterView.OnItemClickListener() {
//            @Override
//            public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
//                Toast.makeText(Select_City.this, station_list.get(i).getCity()+"\nId:- "+station_list.get(i).getCity_id(), Toast.LENGTH_SHORT).show();
//                overridePendingTransition(R.anim.fade_in,R.anim.fade_out);
//                startActivity(new Intent(Select_City.this,User_Home.class).putExtra("city",station_list.get(i).getCity()));
//            }
//        });
//    }
//
//    public void initView() {
//
//        GoogleSignInOptions gso = new GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
//                .requestIdToken(getString(R.string.default_web_client_id))
//                .requestEmail()
//                .build();
//        auth = FirebaseAuth.getInstance();
//        FirebaseUser user = auth.getCurrentUser();
//        mGoogleSignInClient = GoogleSignIn.getClient(Select_City.this, gso);
//    }
//
//    public void setScreen() {
//        actionBar.setElevation(10);
//        actionBar.setTitle("Select City");
//    }
//
//    @Override
//    public boolean onCreateOptionsMenu(Menu menu) {
//        getMenuInflater().inflate(R.menu.logout,menu);
//        return true;
//    }
//
//    @Override
//    public boolean onOptionsItemSelected(MenuItem item) {
//        switch (item.getItemId()){
//            case R.id.logout:
//                auth.signOut();
//                mGoogleSignInClient.signOut();
//                finish();
//                startActivity(new Intent(Select_City.this,User_login.class));
//        }
//        return true;
//    }
//
//    public void find_station() {
//        list.setVisibility(View.GONE);
//        text.addTextChangedListener(new TextWatcher() {
//            @Override
//            public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) { }
//
//            @Override
//            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {
//                station_list.clear();
//                if(charSequence.toString().trim().length() == 0){
//                    list.setVisibility(View.GONE);
//                }
//                else {
//                    list.setVisibility(View.VISIBLE);
//                    cityAdapter.filter(charSequence.toString().toLowerCase(Locale.getDefault()));
//
//                }
//
//            }
//
//            @Override
//            public void afterTextChanged(Editable editable) { }
//        });
//
//    }
//
//
//    private void check_pnr() {
//        check.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View view) {
//                pnr_detail = "";
//                String pnr = text.getText().toString().trim();
//                if(pnr.length() == 10){
//                    Toast.makeText(Select_City.this, "PNR number is: "+ pnr_detail, Toast.LENGTH_SHORT).show();
//                }else {
//                    Toast.makeText(Select_City.this, "Enter valid PNR number.", Toast.LENGTH_SHORT).show();
//                }
//            }
//        });
//    }
//
//    public String loadJSONFromAsset() {
//        String json = null;
//        try {
//            InputStream is = getAssets().open("city.json");
//            int size = is.available();
//            byte[] buffer = new byte[size];
//            is.read(buffer);
//            is.close();
//            json = new String(buffer, "UTF-8");
//        } catch (IOException ex) {
//            ex.printStackTrace();
//            return null;
//        }
//        return json;
//    }
//
//
//}
