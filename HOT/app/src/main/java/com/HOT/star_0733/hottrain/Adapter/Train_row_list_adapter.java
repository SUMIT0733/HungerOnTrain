package com.HOT.star_0733.hottrain.Adapter;

import android.app.Activity;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.HOT.star_0733.hottrain.R;
import com.HOT.star_0733.hottrain.model.Train_list_model;

import java.util.ArrayList;

public class Train_row_list_adapter extends ArrayAdapter<Train_list_model>{

    private Activity context;
    ArrayList<Train_list_model> list;

    public Train_row_list_adapter(@NonNull Activity context, @NonNull ArrayList<Train_list_model> objects) {
        super(context, R.layout.train_row_list, objects);
        this.context = context;
        this.list = objects;
    }

    @NonNull
    @Override
    public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {
        LayoutInflater inflater = context.getLayoutInflater();
        if(convertView == null){
            convertView = inflater.inflate(R.layout.train_row_list,null,true);
        }

        Train_list_model model = list.get(position);

        TextView arrive_time = convertView.findViewById(R.id.arrive_time);
        TextView station_name = convertView.findViewById(R.id.station_name);
        TextView depart_time = convertView.findViewById(R.id.depart_time);
        ImageView station_img = convertView.findViewById(R.id.station_img);

        if(position == 0){
            station_img.setImageResource(R.drawable.station2_start);
        }else if(position == (list.size() - 1)){
            station_img.setImageResource(R.drawable.station2_end);
        }
        else if(model.getName().equals(" ")){
            station_img.setImageResource(R.drawable.station2_space);
        }
        else {
            station_img.setImageResource(R.drawable.station2);
        }

        arrive_time.setText(model.getArrival_time());
        station_name.setText(model.getName());
        depart_time.setText(model.getDepart_time());
        return convertView;
    }
}
