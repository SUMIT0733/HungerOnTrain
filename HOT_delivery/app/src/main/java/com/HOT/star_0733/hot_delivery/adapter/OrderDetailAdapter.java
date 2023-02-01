package com.HOT.star_0733.hot_delivery.adapter;

import android.app.Activity;
import android.content.Context;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import com.HOT.star_0733.hot_delivery.R;
import com.HOT.star_0733.hot_delivery.model.OrderDetail;
import com.HOT.star_0733.hot_delivery.model.OrderModel;

import java.util.ArrayList;
import java.util.List;

public class OrderDetailAdapter extends ArrayAdapter<OrderDetail> {

    Activity context;
    ArrayList<OrderDetail> list;

    public OrderDetailAdapter(@NonNull Activity context, @NonNull ArrayList<OrderDetail> list) {
        super(context, R.layout.order_list_row, list);
        this.context = context;
        this.list = list;
    }

    @NonNull
    @Override
    public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {
        convertView = context.getLayoutInflater().inflate(R.layout.order_list_row,null,true);

        OrderDetail detail = list.get(position);
        TextView name = convertView.findViewById(R.id.name);
        TextView order_id = convertView.findViewById(R.id.order_id);
        TextView time = convertView.findViewById(R.id.time);

        name.setText(detail.getName());
        order_id.setText("Order # "+detail.getOrder());
        time.setText(detail.getTime());
        return convertView;
    }
}
