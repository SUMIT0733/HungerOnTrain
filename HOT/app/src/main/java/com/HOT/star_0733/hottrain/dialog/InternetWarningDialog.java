package com.HOT.star_0733.hottrain.dialog;

import android.content.Context;
import android.net.ConnectivityManager;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.AppCompatButton;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;

import com.HOT.star_0733.hottrain.R;
import com.HOT.star_0733.hottrain.User_login;
import com.google.firebase.auth.FirebaseAuth;

public class InternetWarningDialog extends android.support.v4.app.DialogFragment {
    View view;
    AppCompatButton button;
    FirebaseAuth auth1;
    User_login login;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, Bundle savedInstanceState) {
        view = inflater.inflate(R.layout.internet_warning,null,true);
        login = new User_login();
        auth1 = FirebaseAuth.getInstance();
        return view;
    }

    @Override
    public void onViewCreated(View view, @Nullable Bundle savedInstanceState) {
        button = view.findViewById(R.id.bt_close);
        button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                checkNet();
            }
        });
    }

    private void checkNet() {
        final ConnectivityManager connectivityManager = ((ConnectivityManager) getActivity().getSystemService(Context.CONNECTIVITY_SERVICE));

        if(connectivityManager.getActiveNetworkInfo() != null && connectivityManager.getActiveNetworkInfo().isConnected())
        {
            Toast.makeText(getActivity(), "Found", Toast.LENGTH_SHORT).show();
            getDialog().dismiss();
            if (auth1.getCurrentUser() != null) {
                login.checkUser();
            }
            else {
                getDialog().dismiss();
            }

        }
        else
        {
            Toast.makeText(getActivity(), "No Internet Connection", Toast.LENGTH_SHORT).show();
        }
    }
}
