package com.HOT.star_0733.hot_delivery;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.Button;
import android.widget.TextView;

public class Station_lat_lng extends AppCompatActivity {

    Button btn;
    TextView result;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_station_lat_lng);

        btn = findViewById(R.id.btn);
        result = findViewById(R.id.result);
        
    }
}
