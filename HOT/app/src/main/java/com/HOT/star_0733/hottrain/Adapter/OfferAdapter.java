package com.HOT.star_0733.hottrain.Adapter;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import com.HOT.star_0733.hottrain.R;
import com.HOT.star_0733.hottrain.model.OfferModel;

import java.util.ArrayList;

public class OfferAdapter extends ArrayAdapter<OfferModel> {

    ArrayList<OfferModel> list;
    Activity context;

    public OfferAdapter(@NonNull Activity context, ArrayList<OfferModel> list) {
        super(context, R.layout.offer_row,list);
        this.list = list;
        this.context = context;
    }

    @NonNull
    @Override
    public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {
       convertView = context.getLayoutInflater().inflate(R.layout.offer_row, parent, false);
       TextView offer_code = convertView.findViewById(R.id.offer_code);
       TextView offer_name = convertView.findViewById(R.id.offer_name);
       TextView offer_desc = convertView.findViewById(R.id.offer_desc);

       final OfferModel model = list.get(position);
       offer_code.setText(model.getOffer_code());
       offer_name.setText(model.getOffer_name());
       offer_desc.setOnClickListener(new View.OnClickListener() {
           @Override
           public void onClick(View view) {
               AlertDialog.Builder builder = new AlertDialog.Builder(context)
                     .setMessage(model.getOffer_desc())
                     .setCancelable(true);
               builder.show();
           }
       });
       return convertView;
    }
}
