package com.HOT.star_0733.hottrain;

import android.app.ProgressDialog;
import android.content.Context;
import android.graphics.Color;
import android.location.Address;
import android.location.Geocoder;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Handler;
import android.support.v4.app.FragmentActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.WindowManager;
import android.widget.Toast;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MapStyleOptions;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;
import com.google.android.gms.maps.model.Polyline;
import com.google.android.gms.maps.model.PolylineOptions;
import com.google.gson.JsonObject;
import com.google.maps.android.PolyUtil;
import com.google.maps.model.DirectionsResult;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;


import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.List;
import java.util.Timer;
import java.util.TimerTask;

import cz.msebera.android.httpclient.Header;
import cz.msebera.android.httpclient.entity.StringEntity;


public class MapsActivity extends FragmentActivity implements OnMapReadyCallback {

      private GoogleMap mMap;
      String id,station_name;
      AsyncHttpClient client;
      Double lat,lng;
      Timer timer;
      Double to_lat,to_lng,from_lat,from_lng;
      ConnectivityManager connectivityManager;
      boolean wifi,data;
    DirectionsResult result;
    LatLng latlng;
    Marker delivery;
    String route_data;
    List<LatLng> list;

      @Override
      protected void onCreate(Bundle savedInstanceState) {
            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_maps);
            connectivityManager = ((ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE));

            list = new ArrayList<>();

          id = getIntent().getStringExtra("delivery_id");

          to_lng = getIntent().getDoubleExtra("to_lng",0);
          to_lat = getIntent().getDoubleExtra("to_lat",0);
          from_lat = getIntent().getDoubleExtra("from_lat",0);
          from_lng = getIntent().getDoubleExtra("from_lng",0);
          Log.d("to_lng",to_lng.toString());
          Log.d("to_lat",to_lat.toString());
          Log.d("from_lng",from_lng.toString());
          Log.d("from_lat",from_lat.toString());

          station_name = getIntent().getStringExtra("station");

          SupportMapFragment mapFragment = (SupportMapFragment) getSupportFragmentManager()
                    .findFragmentById(R.id.map);
          mapFragment.getMapAsync(this);
          getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,WindowManager.LayoutParams.FLAG_FULLSCREEN);

      }

    private void getPolyline1() {
        AsyncHttpClient client=new AsyncHttpClient(true,80,443);
        client.addHeader("x-api-key", "EhqYz5KQvEWgzGbk1oEC6h3WubbeOc246QGMfPsa");
        JSONObject jsonParams = new JSONObject();
        try {
            JSONObject to = new JSONObject();
            to.put("lng",to_lng.toString());
            to.put("lat",to_lat.toString());
            jsonParams.put("to", to);

            JSONObject from = new JSONObject();
            from.put("lng",from_lng.toString());
            from.put("lat",from_lat.toString());
            jsonParams.put("from", from);

        } catch (JSONException e) {
            e.printStackTrace();
        }

        StringEntity entity = null;

        try {
            entity = new StringEntity(jsonParams.toString());
        } catch (UnsupportedEncodingException e) {
            e.printStackTrace();
        }

        Log.d("request",jsonParams.toString());
        final ProgressDialog pd=new ProgressDialog(MapsActivity.this);
        pd.setMessage("Please wait");
        client.post(MapsActivity.this,"https://dev.tollguru.com/beta00/calc/here",entity, "application/json",new JsonHttpResponseHandler(){
            @Override
            public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                try {
                      //JSONObject object = new JSONObject(route_data);
                      if(response.getString("status").equals("OK")) {
                          JSONArray array = response.getJSONArray("routes");
                          JSONObject path = array.getJSONObject(0);
                          route_data = path.getString("polyline");
                          list = PolyUtil.decode(route_data);
                          Log.d("polyline",route_data);
                          for (int i = 0; i < list.size() - 1; i++) {
                              LatLng src = list.get(i);
                              LatLng dest = list.get(i + 1);

                              Polyline line = mMap.addPolyline(
                                    new PolylineOptions().add(src).add(dest).width(10).color(Color.BLACK).geodesic(true)
                              );
                          }

                      }else{
                          Toast.makeText(MapsActivity.this, "Some error occured.", Toast.LENGTH_SHORT).show();
                      }

                  } catch (JSONException e) {
                      e.printStackTrace();
                  }
                }

            @Override
            public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                super.onFailure(statusCode, headers, throwable, errorResponse);
            }

            @Override
            public void onStart() {
                pd.show();
            }

            @Override
            public void onFinish() {
                pd.dismiss();
            }
        });
    }

    private void getLatLong(String id) {
            client = new AsyncHttpClient();
            RequestParams params = new RequestParams();
            params.put("id",id);
            client.post(CommonUtil.url+"delivery_latlng.php",params,new JsonHttpResponseHandler(){
                  @Override
                  public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                        try {
                              lat = Double.parseDouble(response.getString("lat"));
                              lng = Double.parseDouble(response.getString("lng"));

                        } catch (JSONException e) {
                              e.printStackTrace();
                        }

                        latlng = new LatLng(lat,lng);
                        if(delivery == null){
                            delivery = mMap.addMarker(new MarkerOptions().position(latlng).title("Delivery Person").icon(BitmapDescriptorFactory.fromResource(R.drawable.google_pin)));
                        }else {
                            delivery.remove();
                            delivery = mMap.addMarker(new MarkerOptions().position(latlng).title("Delivery Person").icon(BitmapDescriptorFactory.fromResource(R.drawable.google_pin)));
                        }
                        mMap.moveCamera(CameraUpdateFactory.newLatLng(latlng));
                        mMap.animateCamera(CameraUpdateFactory.zoomTo(12));

                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) { }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) { }
            });

      }

      @Override
      public void onMapReady(GoogleMap googleMap) {
            mMap = googleMap;
            mMap.setMapStyle(MapStyleOptions.loadRawResourceStyle(this, R.raw.google_style));
            wifi = getWifi();
            data = getData();

            getPolyline1();

            if(!wifi  && !data) {
                  Toast.makeText(MapsActivity.this, "No internet connection.", Toast.LENGTH_SHORT).show();
            }else {
                  getLatLong(id);
            }
            final Handler handler = new Handler();
            timer = new Timer();
            TimerTask doAsynchronousTask = new TimerTask() {
                  @Override
                  public void run() {
                        handler.post(new Runnable() {
                              @SuppressWarnings("unchecked")
                              public void run() {
                                    try {
                                          wifi = getWifi();
                                          data = getData();

                                          if(!wifi  && !data) {
                                                Toast.makeText(MapsActivity.this, "No internet connection.", Toast.LENGTH_SHORT).show();
                                          }else {
                                                getLatLong(id);
                                          }
                                    }
                                    catch (Exception e) {
                                          Log.d("---catch---",e.getMessage());
                                    }
                              }
                        });
                  }
            };

            timer.schedule(doAsynchronousTask, 0,20000);

      }

      @Override
      public void onBackPressed() {
            finish();
            timer.cancel();
            timer.purge();
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
}
