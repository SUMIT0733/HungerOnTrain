package com.HOT.star_0733.hottrain.fragment;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.DialogFragment;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.HOT.star_0733.hottrain.BuildConfig;
import com.HOT.star_0733.hottrain.R;
import com.HOT.star_0733.hottrain.User_login;
import com.HOT.star_0733.hottrain.dialog.FeedbackDialog;
import com.HOT.star_0733.hottrain.dialog.MenuInfoDialog;
import com.HOT.star_0733.hottrain.model.Menu_item;
import com.google.android.gms.auth.api.signin.GoogleSignIn;
import com.google.android.gms.auth.api.signin.GoogleSignInClient;
import com.google.android.gms.auth.api.signin.GoogleSignInOptions;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;
import com.squareup.picasso.Picasso;

import de.hdodenhof.circleimageview.CircleImageView;

public class ProfileFragment extends Fragment {
      View view;
      ConnectivityManager connectivityManager;
      TextView name,support,feedback,share,rate,logout,email;
      CircleImageView image;
      GoogleSignInClient signInClient;
      FirebaseAuth auth;
      FirebaseUser user;
      boolean wifi,data;
      Uri uri;
      SharedPreferences preferences;
      SharedPreferences.Editor editor;
      @Nullable
      @Override
      public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
            view = inflater.inflate(R.layout.profilefragment,container,false);
            connectivityManager = ((ConnectivityManager) getActivity().getSystemService(Context.CONNECTIVITY_SERVICE));
            auth = FirebaseAuth.getInstance();
            user  = auth.getCurrentUser();
            GoogleSignInOptions gso = new GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
                        .requestIdToken(getString(R.string.default_web_client_id))
                        .requestEmail()
                        .build();

            signInClient = GoogleSignIn.getClient(getActivity(), gso);

            preferences = getActivity().getSharedPreferences("HOT",Context.MODE_PRIVATE);
            editor = preferences.edit();
            return view;
      }

      @Override
      public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
            name = view.findViewById(R.id.name);
            email = view.findViewById(R.id.email);
            support = view.findViewById(R.id.support);
            share = view.findViewById(R.id.share);
            rate =  view.findViewById(R.id.rate);
            feedback = view.findViewById(R.id.feedback);
            logout = view.findViewById(R.id.logout);
            image = view.findViewById(R.id.image);

            name.setText(user.getDisplayName());
            email.setText(user.getEmail());
            uri = user.getPhotoUrl();
            Picasso.get().load(uri).into(image);
//            image.setImageURI(user.getPhotoUrl());

            logout.setOnClickListener(new View.OnClickListener() {
                  @Override
                  public void onClick(View view) {
                        showAlert();
                  }
            });

            support.setOnClickListener(new View.OnClickListener() {
                  @Override
                  public void onClick(View view) {
                        Intent intent = new Intent(Intent.ACTION_DIAL);
                        intent.setData(Uri.parse("tel: 1800800123"));
                        startActivity(intent);
                  }
            });

            feedback.setOnClickListener(new View.OnClickListener() {
                  @Override
                  public void onClick(View view) {
                        showDialog();

                  }
            });

            share.setOnClickListener(new View.OnClickListener() {
                  @Override
                  public void onClick(View view) {
                        try {
                              Intent shareIntent = new Intent(Intent.ACTION_SEND);
                              shareIntent.setType("text/plain");
                              shareIntent.putExtra(Intent.EXTRA_SUBJECT, "Share HOT app to other.");
                              String shareMessage= "\nLet me recommend you this application\n\n";
                              shareMessage = shareMessage + "https://play.google.com/store/apps/details?id=com.mysy.star_0733.mysy1";
                              shareIntent.putExtra(Intent.EXTRA_TEXT, shareMessage);
                              startActivity(Intent.createChooser(shareIntent, "choose one"));
                        } catch(Exception e) {
                              //e.toString();
                        }
                  }
            });

            rate.setOnClickListener(new View.OnClickListener() {
                  @Override
                  public void onClick(View view) {
                        startActivity(new Intent(Intent.ACTION_VIEW,
                                    Uri.parse("https://play.google.com/store/apps/details?id=com.mysy.star_0733.mysy1")));
                  }
            });

      }

      private void showDialog() {
            AlertDialog.Builder dialogBuilder = new AlertDialog.Builder(getActivity());
            final LayoutInflater inflater = this.getLayoutInflater();
            final View dialogView = inflater.inflate(R.layout.feedback_dialog, null);
            dialogBuilder.setView(dialogView);

            final EditText edit = dialogView.findViewById(R.id.feedback);

            dialogBuilder.setTitle("Feedback");
            dialogBuilder.setPositiveButton("Submit", new DialogInterface.OnClickListener() {
                  public void onClick(DialogInterface dialog, int whichButton) {
                        String data = edit.getText().toString().trim();

                        if(data.length() > 0){
                              Intent intent = new Intent(Intent.ACTION_SENDTO);
                              intent.setData(Uri.parse("mailto:sumitmonapara@gmail.com")); // only email apps should handle this
                              intent.putExtra(Intent.EXTRA_EMAIL, data);
                              intent.putExtra(Intent.EXTRA_SUBJECT, "Feedback of HOT");
                              intent.putExtra(Intent.EXTRA_TEXT,data);
                              if (intent.resolveActivity(getActivity().getPackageManager()) != null) {
                                    startActivity(intent);
                              }

                        }else{
                              Toast.makeText(getActivity(), "Type Something.", Toast.LENGTH_SHORT).show();
                        }
                  }
            });
            dialogBuilder.setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
                  public void onClick(DialogInterface dialog, int whichButton) {
                        dialog.dismiss();
                  }
            });
            AlertDialog b = dialogBuilder.create();
            b.show();
      }

      private void showAlert() {
            AlertDialog.Builder builder = new AlertDialog.Builder(getActivity(),R.style.Dialog_Theme);
            builder.setTitle("Sign out")
                        .setMessage("Are you sure?")
                        .setPositiveButton("LOGOUT", new DialogInterface.OnClickListener() {
                              @Override
                              public void onClick(DialogInterface dialogInterface, int i) {
                                    auth.signOut();
                                    signInClient.signOut();
                                    getActivity().finish();
                                    editor.clear();
                                    editor.commit();
                                    startActivity(new Intent(getActivity(),User_login.class));
                              }
                        }).setNegativeButton("CANCEL", new DialogInterface.OnClickListener() {
                  @Override
                  public void onClick(DialogInterface dialogInterface, int i) {
                        dialogInterface.dismiss();
                  }
            }).create();
            builder.show();
      }

      private boolean getWifi() {
            NetworkInfo networkInfo = connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI);
            wifi = networkInfo.isConnected();
            return wifi;
      }

      private boolean getData(){
            NetworkInfo networkInfo1 = connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE);
            data = networkInfo1.isConnected();
            return data;
      }
}
