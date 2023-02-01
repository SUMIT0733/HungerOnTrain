package com.HOT.star_0733.hot_delivery;

import android.content.SharedPreferences;
import android.util.Log;

import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.iid.FirebaseInstanceIdService;

public class MyFirebaseInstanceIdService extends FirebaseInstanceIdService {
      private static final String TAG = "";

      @Override
      public void onTokenRefresh() {
            /* Get updated InstanceID token. */
            String refreshedToken = FirebaseInstanceId.getInstance().getToken();
            Log.d(TAG, "Refreshed token: " + refreshedToken);

            /*
            If you want to send messages to this application instance or
            manage this apps subscriptions on the server side, send the
            Instance ID token to your app server.
            */
            sendRegistrationToServer(refreshedToken);
      }

      private void sendRegistrationToServer(String refreshedToken) {
            SharedPreferences sp;
            sp = getApplicationContext().getSharedPreferences("DeviceToken", MODE_PRIVATE);
            SharedPreferences.Editor e = sp.edit();
            e.putString("token", refreshedToken);
            Log.d("token", refreshedToken);
            e.apply();
      }

}
