package com.HOT.star_0733.hot_delivery;

import android.app.ActivityManager;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.support.annotation.NonNull;
import android.support.design.widget.BottomNavigationView;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentTransaction;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.CompoundButton;
import android.widget.Switch;
import android.widget.Toast;

import com.HOT.star_0733.hot_delivery.fragment.GetOrderFragment;
import com.HOT.star_0733.hot_delivery.fragment.HomeFragment;
import com.HOT.star_0733.hot_delivery.fragment.PastOrderFragment;

public class Home extends AppCompatActivity {

      Fragment fragment;
      BottomNavigationView navigation;
      SharedPreferences preferences;
      SharedPreferences.Editor editor;
      GPSTracker gpsTracker;
      Double lat,lng;

      @Override
      protected void onCreate(Bundle savedInstanceState) {
            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_home);

            preferences = getSharedPreferences(CommonUtil.Pref, Context.MODE_PRIVATE);
            editor = preferences.edit();

            navigation = findViewById(R.id.navigation);
            navigation.setItemIconTintList(null);

          fragment = new HomeFragment();
          FragmentTransaction transaction = getSupportFragmentManager().beginTransaction();
          transaction.replace(R.id.frame, fragment);
          transaction.addToBackStack("home1");
          transaction.commit();

          gpsTracker = new GPSTracker(Home.this);
          //gpsTracker.showSettingsAlert();

            navigation.setOnNavigationItemSelectedListener(new BottomNavigationView.OnNavigationItemSelectedListener() {
                  @Override
                  public boolean onNavigationItemSelected(@NonNull MenuItem menuItem) {
                        switch (menuItem.getItemId()){
                              case R.id.order:
                                  fragment = new HomeFragment();
                                  loadFragment(fragment);
                                  break;
                              case R.id.past:
                                    fragment = new PastOrderFragment();
                                    loadFragment(fragment);
                                    break;
                        }
                        return true;
                  };
            });
      }

      @Override
      public void onBackPressed() {
            finish();
      }

      private void loadFragment(Fragment fragment) {
            FragmentTransaction transaction = getSupportFragmentManager().beginTransaction();
            transaction.replace(R.id.frame, fragment);
            transaction.addToBackStack("fragment");
            transaction.commit();
      }

      @Override
      public boolean onCreateOptionsMenu(Menu menu) {
            getMenuInflater().inflate(R.menu.logout,menu);
            return true;
      }

      @Override
      public boolean onOptionsItemSelected(MenuItem item) {
            if(item.getItemId() == R.id.logout){
                  AlertDialog.Builder builder = new AlertDialog.Builder(Home.this,R.style.Dialog_Theme);
                  builder.setTitle("Sign out")
                              .setMessage("Are you sure?")
                              .setPositiveButton("LOGOUT", new DialogInterface.OnClickListener() {
                                    @Override
                                    public void onClick(DialogInterface dialogInterface, int i) {
                                          finish();
                                          editor.clear();
                                          editor.commit();
                                          startActivity(new Intent(Home.this,Login.class));
                                    }
                              }).setNegativeButton("CANCEL", new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialogInterface, int i) {
                              dialogInterface.dismiss();
                        }
                  }).create();
                  builder.show();
            }
            return true;
      }

    private boolean isMyServiceRunning(Class<?> serviceClass) {
        ActivityManager manager = (ActivityManager) getSystemService(Context.ACTIVITY_SERVICE);
        for (ActivityManager.RunningServiceInfo service : manager.getRunningServices(Integer.MAX_VALUE)) {
            if (serviceClass.getName().equals(service.service.getClassName())) {
                return true;
            }
        }
        return false;
    }

    @Override
    protected void onResume() {
        super.onResume();
        if(!isMyServiceRunning(LiveLocationUpdate.class)){
            startService(new Intent(getApplicationContext(),LiveLocationUpdate.class));
//            Toast.makeText(Home.this, "SERVICE START", Toast.LENGTH_SHORT).show();
        }
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        stopService(new Intent(getApplicationContext(), LiveLocationUpdate.class));
//        Toast.makeText(Home.this, "SERVICE STOPED.", Toast.LENGTH_SHORT).show();
    }
}
