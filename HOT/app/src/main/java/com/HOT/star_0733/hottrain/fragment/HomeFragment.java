package com.HOT.star_0733.hottrain.fragment;


import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.inputmethod.InputMethodManager;
import android.widget.AdapterView;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.HOT.star_0733.hottrain.Adapter.Train_row_list_adapter;
import com.HOT.star_0733.hottrain.CommonUtil;

import com.HOT.star_0733.hottrain.R;
import com.HOT.star_0733.hottrain.Restaurant_list;
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

public class HomeFragment extends Fragment {

      Calendar c;
      SharedPreferences sharedPreferences;
      public static final String MyPREFERENCES = "HOT";
      ConnectivityManager connectivityManager;
      boolean wifi,data;
      GoogleSignInClient mGoogleSignInClient;
      FirebaseAuth auth;
      SharedPreferences preferences;
      SharedPreferences.Editor editor;
      String train_time,current_time;
      String names,emails,id;
      Train_row_list_adapter adapter;
      ArrayList<Train_list_model> arrayList;
      AsyncHttpClient client;
      ACProgressFlower dialog;
      LinearLayout list_header;
      EditText train_number;
      CircleImageView get_train;
      TextView train_name,status;
      ListView list;
      View header_line;
      CircularProgressBar progressBar;
      ProgressDialog pd;
      String train_number_str;
      String tmp_arrival,tmp_depart,arrival,depart,station_name,code;
      View view;
      @Nullable
      @Override
      public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
            view = inflater.inflate(R.layout.homefragment,container,false);
            connectivityManager = ((ConnectivityManager) getActivity().getSystemService(Context.CONNECTIVITY_SERVICE));
            initView();
            getPref();
            deleteCart(id);
            return view;
      }

      @Override
      public void onViewCreated(@NonNull final View view, @Nullable Bundle savedInstanceState) {
            wifi = getWifi();
            data = getData();

            c = Calendar.getInstance(Locale.getDefault());
            Date currentLocalTime = c.getTime();
            DateFormat date = new SimpleDateFormat("HH:mm");
            date.setTimeZone(TimeZone.getDefault());
            current_time = date.format(currentLocalTime);

            if (!wifi && !data) {
                  Toast.makeText(getActivity(), "No Internet Connection.", Toast.LENGTH_SHORT).show();
                  android.support.v7.app.AlertDialog.Builder builder = new android.support.v7.app.AlertDialog.Builder(getActivity());
                  builder.setTitle("Error")
                              .setMessage("No internet connection.Try again.")
                              .setCancelable(false)
                              .setNegativeButton("cancel", new DialogInterface.OnClickListener() {
                                    @Override
                                    public void onClick(DialogInterface dialogInterface, int i) {
                                          dialogInterface.dismiss();
                                          getActivity().finish();
                                    }
                              }).show();
            } else {
                  loadJSONFromAsset("karnavati.json");
            }

            //handle enter key on type in edittext
            train_number.setOnKeyListener(new View.OnKeyListener() {
                  public boolean onKey(View v, int keyCode, KeyEvent event) {
                        // If the event is a key-down event on the "enter" button
                        if ((keyCode == KeyEvent.KEYCODE_ENTER)) {
                              // Perform action on key press
                              wifi = getWifi();
                              data = getData();
                              if (!wifi && !data) {
                                    Toast.makeText(getActivity(), "No Internet Connection", Toast.LENGTH_SHORT).show();
                                    android.support.v7.app.AlertDialog.Builder builder = new android.support.v7.app.AlertDialog.Builder(getActivity(), R.style.Dialog_Theme);
                                    builder.setTitle("Error")
                                                .setMessage("No internet connection.Try again.")
                                                .setCancelable(false)
                                                .setNegativeButton("ok", new DialogInterface.OnClickListener() {
                                                      @Override
                                                      public void onClick(DialogInterface dialogInterface, int i) {
                                                            dialogInterface.dismiss();
                                                            getActivity().finish();
                                                      }
                                                }).show();
                              } else {
                                    hideSoftKeyboard(getActivity(), view);
                                    arrayList.clear();
                                    list_header.setVisibility(View.GONE);
                                    header_line.setVisibility(View.GONE);
                                    train_name.setText("Train name");
                                    train_number_str = train_number.getText().toString().trim();
                                    if (train_number_str == null || train_number_str.length() < 4) {
                                          Toast.makeText(getActivity(), "Invalid train number.", Toast.LENGTH_SHORT).show();
                                    } else {
//                                          if (train_number_str.equals("19033")) {
//                                                loadJSONFromAsset("queen.json");
//                                          } else if (train_number_str.equals("12933")) {
//                                                loadJSONFromAsset("karnavati.json");
//                                          } else {
//                                                Toast.makeText(getActivity(), "Invalid train number.", Toast.LENGTH_SHORT).show();
//                                          }
                                          //loadList();
                                          getTrainInfo();
                                    }
                              }
                              return true;
                        }
                        return false;
                  }
            });

            get_train.setOnClickListener(new View.OnClickListener() {
                  @Override
                  public void onClick(View view) {

                        wifi = getWifi();
                        data = getData();
                        if (!wifi && !data) {
                              Toast.makeText(getActivity(), "No Internet Connection", Toast.LENGTH_SHORT).show();
                              android.support.v7.app.AlertDialog.Builder builder = new android.support.v7.app.AlertDialog.Builder(getActivity(), R.style.Dialog_Theme);
                              builder.setTitle("Error")
                                          .setMessage("No internet connection.Try again.")
                                          .setCancelable(false)
                                          .setNegativeButton("ok", new DialogInterface.OnClickListener() {
                                                @Override
                                                public void onClick(DialogInterface dialogInterface, int i) {
                                                      dialogInterface.dismiss();
                                                      getActivity().finish();
                                                }
                                          }).show();
                        } else {
                              hideSoftKeyboard(getActivity(), view);
                              arrayList.clear();
                              list_header.setVisibility(View.GONE);
                              header_line.setVisibility(View.GONE);
                              train_name.setText("Train name");
                              train_number_str = train_number.getText().toString().trim();
                              if (train_number_str == null || train_number_str.length() < 4) {
                                    Toast.makeText(getActivity(), "Invalid train number.", Toast.LENGTH_SHORT).show();
                              } else {
                                    if (train_number_str.equals("19033")) {
                                          loadJSONFromAsset("queen.json");
                                    } else if (train_number_str.equals("12933")) {
                                          loadJSONFromAsset("karnavati.json");
                                    } else {
                                          Toast.makeText(getActivity(), "Invalid train number.", Toast.LENGTH_SHORT).show();
                                    }
                                    //loadList();
                                    //getTrainInfo();
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
                              Toast.makeText(getActivity(), "No Internet Connection.", Toast.LENGTH_SHORT).show();
                              android.support.v7.app.AlertDialog.Builder builder = new android.support.v7.app.AlertDialog.Builder(getActivity());
                              builder.setTitle("Error")
                                          .setMessage("No internet connection.Try again.")
                                          .setCancelable(false)
                                          .setNegativeButton("cancel", new DialogInterface.OnClickListener() {
                                                @Override
                                                public void onClick(DialogInterface dialogInterface, int i) {
                                                      dialogInterface.dismiss();
                                                      getActivity().finish();
                                                }
                                          }).show();
                        }else {
                              if(model.getName().equals(" ")){
                              }else {
                                    String station = model.getName();
                                    String[] arr = station.split(" ");
                                    String result = "";
                                    if (arr.length > 0) {
                                          result = station.substring(0, station.lastIndexOf(" " + arr[arr.length-1]));
                                    }
                                    // Toast.makeText(getActivity, ""+current_time+" "+model.getArrival_time(), Toast.LENGTH_SHORT).show();
                                    if((toMinute(model.getArrival_time()) - toMinute(current_time) >= 60)) {
                                          getCity(result, model.getArrival_time());
                                    }
                                    else {
                                          Toast.makeText(getActivity(), "You have to place order atleast 1 hour before arriving at the station.", Toast.LENGTH_SHORT).show();

                                          getCity(result, model.getArrival_time());
                                    }
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

      public void getTrainInfo() {

            client.get("https://api.railwayapi.com/v2/route/train/"+train_number_str+"/apikey/"+CommonUtil.Train_API+"/",new JsonHttpResponseHandler(){
                  @Override
                  public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                        super.onSuccess(statusCode, headers, response);

                        list_header.setVisibility(View.VISIBLE);
                        header_line.setVisibility(View.VISIBLE);
                        progressBar.setVisibility(View.GONE);
                        Log.d("----trainNumber----",response.toString());

                        try {
                            JSONObject train = response.getJSONObject("train");
                            if (train.getString("name").equals(null)) {
                                Toast.makeText(getActivity(), "Invalid train number", Toast.LENGTH_SHORT).show();
                            } else {
                                train_name.setText(train.getString("name") + "  ( " + train.getString("number") + " )");
                            }
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

                              adapter = new Train_row_list_adapter(getActivity(),arrayList);
                              list.setAdapter(adapter);

                        } catch (JSONException e) {
                              e.printStackTrace();
                        }


                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                        super.onFailure(statusCode, headers, responseString, throwable);
                        progressBar.setVisibility(View.GONE);
                        Toast.makeText(getActivity(), "Some Error occured.Try after sometime!", Toast.LENGTH_SHORT).show();
                        Log.d("----error----",responseString);
                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                        super.onFailure(statusCode, headers, throwable, errorResponse);
                        progressBar.setVisibility(View.GONE);
                        Toast.makeText(getActivity(), "Some Error occured.Try after sometime.", Toast.LENGTH_SHORT).show();
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

      public void loadJSONFromAsset(String file_name) {
            String json = null;
            try {
                  InputStream is = getActivity().getAssets().open(file_name);
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
            //Toast.makeText(getActivity(), response.toString(), Toast.LENGTH_SHORT).show();

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

                  adapter = new Train_row_list_adapter(getActivity(),arrayList);
                  list.setAdapter(adapter);

            } catch (JSONException e) {
                  e.printStackTrace();
            }
      }
      
      public void getPref() {
            sharedPreferences = getActivity().getSharedPreferences(MyPREFERENCES, Context.MODE_PRIVATE);

            names = sharedPreferences.getString("name", "john");
            emails = sharedPreferences.getString("email", "abcd@abc.com");
            id = sharedPreferences.getString("id", "100");
      }

      public int toMinute(String time){

            int int_time = Integer.parseInt(time.split(":")[0])*60 + Integer.parseInt(time.split(":")[1]);
            return int_time;
      }

      public void getCity(final String result, final String arrival_time) {
            RequestParams params = new RequestParams();
            params.put("station",result);

            Log.d("station",result);
            client.post(CommonUtil.url+"getcity.php",params,new JsonHttpResponseHandler(){
                  @Override
                  public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                        super.onSuccess(statusCode, headers, response);
                        //dialog.dismiss();
                        try {
                              String responce = response.getString("responce");
                              Log.d("station",response.toString());
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
                     //   dialog.dismiss();
                        Toast.makeText(getActivity(), "Error occured.", Toast.LENGTH_SHORT).show();

                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                        super.onFailure(statusCode, headers, responseString, throwable);
                        Toast.makeText(getActivity(), " "+responseString, Toast.LENGTH_SHORT).show();
                        //dialog.dismiss();
                  }

                  @Override
                  public void onStart() {
                        super.onStart();
                        //dialog.show();
                        pd.setMessage("Please wait...");
                        pd.show();

                  }

                  @Override
                  public void onFinish() {
                        super.onFinish();
                        //dialog.dismiss();
                        pd.dismiss();
                  }
            });
      }

      public void showAlertError() {
            AlertDialog.Builder builder = new AlertDialog.Builder(getActivity(),R.style.Dialog_Theme);
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
            AlertDialog.Builder builder = new AlertDialog.Builder(getActivity(),R.style.Dialog_Theme);
            builder.setTitle("Confirmation")
                        .setMessage("You will get the food from "+city+" .")
                        .setPositiveButton("Proceed", new DialogInterface.OnClickListener() {
                              @Override
                              public void onClick(DialogInterface dialogInterface, int i) {
                                    startActivity(new Intent(getActivity(),Restaurant_list.class)
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

      public void initView() {
            GoogleSignInOptions gso = new GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
                        .requestIdToken(getString(R.string.default_web_client_id))
                        .requestEmail()
                        .build();
            auth = FirebaseAuth.getInstance();
            FirebaseUser user = auth.getCurrentUser();
            mGoogleSignInClient = GoogleSignIn.getClient(getActivity(), gso);

            preferences = getActivity().getSharedPreferences("train", Context.MODE_PRIVATE);
            editor = preferences.edit();
            progressBar = view.findViewById(R.id.progress);
            train_number = view.findViewById(R.id.train_number);
            train_name = view.findViewById(R.id.train_name);
            get_train = view.findViewById(R.id.get_train);
            //status = findViewById(R.id.status);
            list_header = view.findViewById(R.id.list_header);
            list = view.findViewById(R.id.list);
            header_line = view.findViewById(R.id.header_line);
            arrayList = new ArrayList<>();
            client = new AsyncHttpClient();

            dialog = new ACProgressFlower.Builder(getActivity())
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
            pd = new ProgressDialog(getActivity());
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

      public static void hideSoftKeyboard (Activity activity, View view)
      {
            InputMethodManager imm = (InputMethodManager)activity.getSystemService(Context.INPUT_METHOD_SERVICE);
            imm.hideSoftInputFromWindow(view.getApplicationWindowToken(), 0);
      }


}
