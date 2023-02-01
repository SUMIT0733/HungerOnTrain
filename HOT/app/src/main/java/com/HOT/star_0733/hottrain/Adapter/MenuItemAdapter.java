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
import com.HOT.star_0733.hottrain.model.Menu_item;

import java.util.ArrayList;

public class MenuItemAdapter extends ArrayAdapter<Menu_item> {

    ArrayList<Menu_item> arrayList;
    Activity context;
    TextView food_name,food_cuisine,food_price;
    ImageView food_type;

    public MenuItemAdapter(@NonNull Activity context, @NonNull ArrayList<Menu_item> list) {
        super(context, R.layout.menu_item, list);
        this.context = context;
        this.arrayList = list;
    }

    @NonNull
    @Override
    public View getView(int i, @Nullable View convertView, @NonNull ViewGroup parent) {

        Menu_item menu_item = arrayList.get(i);
        LayoutInflater inflater = context.getLayoutInflater();
        convertView = inflater.inflate(R.layout.menu_item,null,true);

        food_name = convertView.findViewById(R.id.food_name);
        food_cuisine = convertView.findViewById(R.id.food_cuisine);
        food_price = convertView.findViewById(R.id.food_price);
        food_type = convertView.findViewById(R.id.food_type);

            food_name.setText(menu_item.getName());
              food_cuisine.setText("in "+menu_item.getCuisine());
              food_price.setText("\u20b9  "+menu_item.getPrice());

              if(menu_item.getVeg() == 1){
                  food_type.setImageResource(R.drawable.veg);
              }
              else {
                  food_type.setImageResource(R.drawable.nonveg);
              }
        return convertView;
    }
}
