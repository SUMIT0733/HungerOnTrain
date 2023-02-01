package com.HOT.star_0733.hot_delivery;

import android.app.Notification;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.Build;
import android.support.v4.app.NotificationCompat;
import android.util.Log;

import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;

public class MyFirebaseMessagingService extends FirebaseMessagingService {
      private static final String TAG = "___++++++____+++++";
      private NotificationManager mNotificationManager;

      String notificationTitle = null, notificationBody = null;

      @Override
      public void onMessageReceived(RemoteMessage remoteMessage) {

            if (remoteMessage.getNotification() != null) {
                  Log.d(TAG, "Message Notification Body: " + remoteMessage.getNotification().getBody());
                  Log.d(TAG,"title"+remoteMessage.getNotification().getTitle());
                  notificationTitle = remoteMessage.getNotification().getTitle();
                  notificationBody = remoteMessage.getNotification().getBody();

            }
            sendNotification( remoteMessage.getData().get("title"), remoteMessage.getData().get("body"));
//            sendNotification(notificationTitle,notificationBody);
      }

      private void sendNotification(String notificationTitle, String notificationBody) {

            NotificationCompat.Builder mBuilder = new NotificationCompat.Builder(getApplicationContext(), "notify_001");
            Intent ii = new Intent(getApplicationContext(), Splash.class);
            Uri defaultSoundUri= RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
            PendingIntent pendingIntent = PendingIntent.getActivity(MyFirebaseMessagingService.this, 0, ii, PendingIntent.FLAG_ONE_SHOT);


            mBuilder.setContentIntent(pendingIntent);
            mBuilder.setSmallIcon(R.drawable.deliveryman);
            mBuilder.setBadgeIconType(R.drawable.deliveryman);
            mBuilder.setContentTitle(notificationTitle);
            mBuilder.setStyle(new NotificationCompat.BigTextStyle().bigText(notificationBody));
            mBuilder.setContentText(notificationBody);
            mBuilder.setPriority(Notification.PRIORITY_MAX);
            mBuilder.setSound(defaultSoundUri);
            mBuilder.setAutoCancel(true);


            mNotificationManager = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);


            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
                  NotificationChannel channel = new NotificationChannel("notify_001", "BVM IT", NotificationManager.IMPORTANCE_DEFAULT);
                  mNotificationManager.createNotificationChannel(channel);
            }
            mNotificationManager.notify(0, mBuilder.build());
      }
}
