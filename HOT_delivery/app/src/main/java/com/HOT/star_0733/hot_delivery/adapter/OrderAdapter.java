package com.HOT.star_0733.hot_delivery.adapter;

import android.app.Activity;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.HOT.star_0733.hot_delivery.R;
import com.HOT.star_0733.hot_delivery.model.OrderModel;

import java.util.ArrayList;

public class OrderAdapter extends ArrayAdapter<OrderModel> {

      Activity context;
      ArrayList<OrderModel> list;
      public OrderAdapter(@NonNull Activity context, @NonNull ArrayList<OrderModel> list) {
            super(context, R.layout.order_item_row, list);
            this.context = context;
            this.list = list;
      }

      @NonNull
      @Override
      public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {
            convertView = context.getLayoutInflater().inflate(R.layout.order_item_row,null,true);

            OrderModel model = list.get(position);
            ImageView food_type = convertView.findViewById(R.id.food_type);
            TextView food_name = convertView.findViewById(R.id.food_name);
            TextView unit = convertView.findViewById(R.id.unit);
            TextView total = convertView.findViewById(R.id.total);

            food_name.setText(model.getName());
            unit.setText(model.getUnit());
            total.setText(model.getTotal());
            if (model.getVeg() == 1) {
                  food_type.setImageResource(R.drawable.veg);
            } else {
                  food_type.setImageResource(R.drawable.nonveg);
            }
            return convertView;
      }
}