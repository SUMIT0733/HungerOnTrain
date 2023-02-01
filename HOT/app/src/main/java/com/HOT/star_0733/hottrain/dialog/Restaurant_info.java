package com.HOT.star_0733.hottrain.dialog;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.DialogFragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.RatingBar;
import android.widget.TextView;
import android.widget.Toast;

import com.HOT.star_0733.hottrain.R;
import com.HOT.star_0733.hottrain.model.Restaurant_list_model;

public class Restaurant_info extends DialogFragment {

      View view;
      ImageButton close;
      ImageView img;
      TextView name,email,city,address;
      RatingBar ratingBar;
      Bundle bundle;
      Restaurant_list_model model;
      @Nullable
      @Override
      public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
            view = inflater.inflate(R.layout.restaurant_info,null,true);
            bundle = getArguments();
            model = bundle.getParcelable("data");
            return view;
      }

      @Override
      public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
            super.onViewCreated(view, savedInstanceState);
            close = view.findViewById(R.id.bt_close);
            img = view.findViewById(R.id.img);
            name = view.findViewById(R.id.name);
            email = view.findViewById(R.id.email);
            city = view.findViewById(R.id.city);
            address = view.findViewById(R.id.address);
            ratingBar = view.findViewById(R.id.ratingbar);

            name.setText(model.getRestaurant_name());
            email.setText(model.getEmail());
            email.setOnClickListener(new View.OnClickListener() {
                  @Override
                  public void onClick(View view) {
                        Intent emailIntent = new Intent(Intent.ACTION_SEND);
                        emailIntent.setData(Uri.parse("mailto:"));
                        emailIntent.setType("text/plain");
                        emailIntent.putExtra(Intent.EXTRA_EMAIL, model.getEmail());
                        emailIntent.setType("text/rfc822");
                        Intent mailer = Intent.createChooser(emailIntent, "Choose app to send mail.");
                        startActivity(mailer);
                  }
            });
            address.setText(model.getRestaurant_address() +" - "+model.getPincode());
            city.setText(model.getCity()+" - "+model.getState());
            ratingBar.setRating(model.getRating());

            close.setOnClickListener(new View.OnClickListener() {
                  @Override
                  public void onClick(View view) {
                        getDialog().dismiss();
                  }
            });
      }
}
