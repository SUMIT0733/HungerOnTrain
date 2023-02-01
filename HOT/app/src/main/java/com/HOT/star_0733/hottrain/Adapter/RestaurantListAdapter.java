package com.HOT.star_0733.hottrain.Adapter;

import android.app.Activity;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.RatingBar;
import android.widget.TextView;

import com.HOT.star_0733.hottrain.R;
import com.HOT.star_0733.hottrain.model.Restaurant_list_model;
import com.squareup.picasso.Picasso;

import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class RestaurantListAdapter extends ArrayAdapter<Restaurant_list_model> {

    private Activity context;
    private List<Restaurant_list_model> list;
    private List<Restaurant_list_model> filter_list;
    String line;

    public RestaurantListAdapter(@NonNull Activity context, List<Restaurant_list_model> list) {
        super(context,R.layout.restaurant_list_raw,list);
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
            convertView = inflater.inflate(R.layout.restaurant_list_raw,null,true);
        }

          ImageView img = convertView.findViewById(R.id.img);
        TextView name = convertView.findViewById(R.id.name);
        TextView cuisins = convertView.findViewById(R.id.cuisins);
        TextView duration = convertView.findViewById(R.id.duration);
        RatingBar ratingbar = convertView.findViewById(R.id.ratingbar);

        Restaurant_list_model model = list.get(position);

        ratingbar.setRating(model.getRating());
        line = model.getCuisine();
        if(line.equals("false")){
            cuisins.setText(" ");
        }
        else{
            if(line.length() > 35){
                cuisins.setText(line.substring(0,30)+"...");
            }else {
                cuisins.setText(line);
            }
        }
        name.setText(model.getRestaurant_name());
//        price.setText("\u20B9  "+model.getAvg_price()+" / person");
        duration.setText(model.getPincode());
        if(model.getUrl().equals(null)){
              img.setImageResource(R.drawable.food);
        }else {
              Picasso.get()
                          .load(model.getUrl())
                          .placeholder(R.drawable.food)
                          .into(img);
        }

        return convertView;
    }

    public void filter(String charText) {
        charText = charText.toLowerCase(Locale.getDefault());
        list.clear();
        if (charText.length() == 0) {
            list.addAll(filter_list);
        } else {
            for (Restaurant_list_model wp : filter_list) {
                if (wp.getRestaurant_name().toLowerCase(Locale.getDefault()).contains(charText) || wp.getCuisine().toLowerCase(Locale.getDefault()).contains(charText)) {
                    list.add(wp);
                }
            }
        }
        notifyDataSetChanged();
    }
}
