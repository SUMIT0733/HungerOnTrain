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

import com.HOT.star_0733.hottrain.model.HistoryModel;

import java.util.ArrayList;

public class HistoryAdapter extends ArrayAdapter<HistoryModel>{

      Activity activity;
      ArrayList<HistoryModel> list;
      public HistoryAdapter(@NonNull Activity context, @NonNull ArrayList<HistoryModel> list) {
            super(context, R.layout.orderhistoryraw,list);
            this.activity = context;
            this.list = list;
      }

      @NonNull
      @Override
      public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {
            LayoutInflater inflater = activity.getLayoutInflater();
            convertView = inflater.inflate(R.layout.orderhistoryraw,null,true);

            HistoryModel model = list.get(position);

            TextView name = convertView.findViewById(R.id.name);
            TextView order = convertView.findViewById(R.id.order);
            TextView date = convertView.findViewById(R.id.date);
            TextView amount = convertView.findViewById(R.id.amount);

            name.setText(model.getName());
            order.setText(model.getOrder());
            date.setText(model.getTime());
            int ori = Integer.parseInt(model.getPrice());
            int effect = Integer.parseInt(model.getEffect());
            int final_amt = effect + 10;
            amount.setText("\u20b9  "+final_amt);
            return convertView;
      }
}
