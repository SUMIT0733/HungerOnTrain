package com.HOT.star_0733.hottrain.Adapter;

import android.app.Activity;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import com.HOT.star_0733.hottrain.R;
import com.HOT.star_0733.hottrain.model.City;

import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class CityAdapter extends ArrayAdapter<City>{

    private Activity context;
    private List<City> list;
    private List<City> filter_list;

    public CityAdapter(@NonNull Activity context, List<City> list) {
        super(context, R.layout.station_list,list);
        this.context = context;
        this.list = list;
        filter_list = new ArrayList<>();
        filter_list.addAll(list);
    }

    @NonNull
    @Override
    public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {
        LayoutInflater inflater = context.getLayoutInflater();
        if(convertView == null){
            convertView = inflater.inflate(R.layout.station_list,null,true);
        }

        TextView text1 = convertView.findViewById(R.id.text);

        City city = list.get(position);
        text1.setText(city.getCity());

        return convertView;
    }

    public void filter(String charText) {
        charText = charText.toLowerCase(Locale.getDefault());
        list.clear();
        if (charText.length() == 0) {
            list.addAll(filter_list);
        } else {
            for (City wp : filter_list) {
                if (wp.getCity().toLowerCase(Locale.getDefault()).startsWith(charText)) {
                    list.add(wp);
                }
            }
        }
        notifyDataSetChanged();
    }
}
