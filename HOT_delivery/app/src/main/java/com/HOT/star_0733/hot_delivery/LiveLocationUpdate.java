package com.HOT.star_0733.hot_delivery;

import android.app.Service;
import android.content.Intent;
import android.os.Handler;
import android.os.IBinder;
import android.support.annotation.Nullable;
import android.util.Log;
import android.widget.Toast;

import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.RequestParams;
import com.loopj.android.http.TextHttpResponseHandler;

import cz.msebera.android.httpclient.Header;

public class LiveLocationUpdate extends Service {
    String userid;
    private GPSTracker gpsTracker;

    boolean flag=true;

    @Nullable
    @Override
    public IBinder onBind(Intent intent) {
        Log.d("LiveLocation","Binded");
        return null;
    }

    private void updateLocation() {
        userid=getSharedPreferences(CommonUtil.Pref,MODE_PRIVATE).getString("id","");
        AsyncHttpClient client=new AsyncHttpClient();
        RequestParams rp=new RequestParams();
        rp.put("userid",userid);
        rp.put("lat",gpsTracker.getLatitude()+"");
        rp.put("lng",gpsTracker.getLongitude()+"");
        rp.put("function","UpdateLatLng");
        //Toast.makeText(getApplicationContext(), ""+gpsTracker.getLatitude()+"///"+gpsTracker.getLongitude(), Toast.LENGTH_SHORT).show();
        client.post(CommonUtil.url+"delivery_person.php", rp, new TextHttpResponseHandler() {
            @Override
            public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                Log.d("Status","Fail");
                Handler h=new Handler();
                h.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        if(flag)
                            updateLocation();
                    }
                },30000);
            }

            @Override
            public void onSuccess(int statusCode, Header[] headers, String responseString) {
                Log.d("Status","Success"+ gpsTracker.getLatitude()+" "+gpsTracker.getLongitude());
                //Toast.makeText(LiveLocationUpdate.this, "Success"+ gpsTracker.getLatitude()+" "+gpsTracker.getLongitude(), Toast.LENGTH_SHORT).show();
                Handler h=new Handler();
                h.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        if(flag)
                            updateLocation();
                    }
                },30000);
            }
        });
    }

    @Override
    public void onCreate() {
        super.onCreate();

        flag=true;
        Log.d("LiveLocation","created");
        gpsTracker = new GPSTracker(LiveLocationUpdate.this);
        updateLocation();
    }

    @Override
    public void onDestroy() {
        flag=false;
        Log.d("LiveLocation","Destroy");
        super.onDestroy();

    }

}
