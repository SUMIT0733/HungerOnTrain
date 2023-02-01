package com.HOT.star_0733.hottrain;

import android.content.Intent;
import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.view.WindowManager;
import android.view.animation.AccelerateInterpolator;
import android.view.animation.AlphaAnimation;
import android.view.animation.Animation;
import android.widget.ImageView;

import com.HOT.star_0733.hottrain.R;

public class Splash extends AppCompatActivity {

    ImageView img;
    int SPLASH_TIME_OUT = 1000;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash);

        //hide the ActionBar and notification bar
        getSupportActionBar().hide();
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,WindowManager.LayoutParams.FLAG_FULLSCREEN);

        //find image and run the thread
        img = findViewById(com.HOT.star_0733.hottrain.R.id.img);
        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                fadeOutAndHideImage(img);
            }
        }, SPLASH_TIME_OUT);

    }

    public void fadeOutAndHideImage(final ImageView img)
    {
        Animation fadeOut = new AlphaAnimation(1, 0);
        fadeOut.setInterpolator(new AccelerateInterpolator());
        fadeOut.setDuration(500);

        fadeOut.setAnimationListener(new Animation.AnimationListener()
        {
            @Override
            public void onAnimationStart(Animation animation) {}

            public void onAnimationEnd(Animation animation)
            {
                img.setVisibility(View.GONE);
                finish();
                Intent intent = new Intent(Splash.this,User_login.class);
                overridePendingTransition(com.HOT.star_0733.hottrain.R.anim.fade_in, com.HOT.star_0733.hottrain.R.anim.fade_out);
                startActivity(intent);
            }
            @Override
            public void onAnimationRepeat(Animation animation) {}
        });
        img.startAnimation(fadeOut);
    }
}
