package com.HOT.star_0733.hottrain;

import android.support.annotation.NonNull;
import android.support.design.widget.BottomNavigationView;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentTransaction;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.MenuItem;

import com.HOT.star_0733.hottrain.R;
import com.HOT.star_0733.hottrain.fragment.HomeFragment;
import com.HOT.star_0733.hottrain.fragment.OrderHistoryFragment;
import com.HOT.star_0733.hottrain.fragment.ProfileFragment;


public class Home extends AppCompatActivity {

      Fragment fragment;
      BottomNavigationView navigation;
      @Override
      protected void onCreate(Bundle savedInstanceState) {
            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_home);

            navigation = findViewById(R.id.navigation);
            navigation.setItemIconTintList(null);

            fragment = new HomeFragment();
            FragmentTransaction transaction = getSupportFragmentManager().beginTransaction();
            transaction.replace(R.id.frame, fragment);
            transaction.addToBackStack("home1");
            transaction.commit();

            navigation.setOnNavigationItemSelectedListener(new BottomNavigationView.OnNavigationItemSelectedListener() {
                  @Override
                  public boolean onNavigationItemSelected(@NonNull MenuItem item) {
                        switch (item.getItemId()){
                              case R.id.home:
                                    fragment = new HomeFragment();
                                    FragmentTransaction transaction = getSupportFragmentManager().beginTransaction();
                                    transaction.replace(R.id.frame, fragment);
                                    transaction.commit();
                                    break;

                              case R.id.history:
                                    fragment = new OrderHistoryFragment();
                                    loadFragment(fragment);
                                    break;
                              case R.id.profile:
                                    fragment = new ProfileFragment();
                                    loadFragment(fragment);
                                    break;
                        }
                  return true;
                  }
            });

      }

      private void loadFragment(Fragment fragment) {
            FragmentTransaction transaction = getSupportFragmentManager().beginTransaction();
            transaction.replace(R.id.frame, fragment);
            transaction.addToBackStack("fragment");
            transaction.commit();
      }

      @Override
      public void onBackPressed() {
            if(navigation.getSelectedItemId() == R.id.home){
                  finish();
            }
            else
            {
                  FragmentManager fm = getSupportFragmentManager();
                  FragmentTransaction ft = fm.beginTransaction();
                  if(fm.getBackStackEntryCount() > 1)
                  {
                        fm.popBackStack("home1",0);
                        navigation.setSelectedItemId(R.id.home);
                        ft.commit();
                  }
                  else{
                        finish();
                  }
            }
      }
}
