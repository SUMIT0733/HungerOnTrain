package com.HOT.star_0733.hottrain.dialog;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.design.widget.TextInputEditText;
import android.support.v4.app.DialogFragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.HOT.star_0733.hottrain.R;

public class FeedbackDialog extends DialogFragment  {

      TextInputEditText edit;
      TextView submit;
      View view;
      @Nullable
      @Override
      public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
            view = inflater.inflate(R.layout.feedback_dialog, container, false);

            int width = getResources().getDimensionPixelSize(R.dimen.popup_width);
            int height = getResources().getDimensionPixelSize(R.dimen.popup_height);
            getDialog().getWindow().setLayout(width, height);

            return view;
      }

      @Override
      public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
            super.onViewCreated(view, savedInstanceState);
            edit = view.findViewById(R.id.feedback);

            submit.setOnClickListener(new View.OnClickListener() {
                  @Override
                  public void onClick(View view) {
                        String data = edit.getText().toString().trim();

                        if(data.length() > 0){
                              Intent intent = new Intent(Intent.ACTION_SENDTO);
                              intent.setData(Uri.parse("mailto:")); // only email apps should handle this
                              intent.putExtra(Intent.EXTRA_EMAIL, data);
                              intent.putExtra(Intent.EXTRA_SUBJECT, "Feedback of HOT");
                              if (intent.resolveActivity(getActivity().getPackageManager()) != null) {
                                    startActivity(intent);
                              }
                        }
                  }
            });
      }
}
