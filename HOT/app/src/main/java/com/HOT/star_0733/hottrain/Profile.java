package com.HOT.star_0733.hottrain;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.HOT.star_0733.hottrain.R;
import com.google.android.gms.auth.api.signin.GoogleSignIn;
import com.google.android.gms.auth.api.signin.GoogleSignInClient;
import com.google.android.gms.auth.api.signin.GoogleSignInOptions;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;

public class Profile extends AppCompatActivity {

    Button home;
    FirebaseAuth auth;
    String names,emails,id;
    TextView name,email;
    Intent intent;
    GoogleSignInClient mGoogleSignInClient;
    SharedPreferences sharedPreferences;
    public static final String MyPREFERENCES = "HOT";


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_profile);

        sharedPreferences = getSharedPreferences(MyPREFERENCES, Context.MODE_PRIVATE);

        names = sharedPreferences.getString("name","john");
        emails = sharedPreferences.getString("email","abcd@abc.com");
        id = sharedPreferences.getString("id","100");

        name = findViewById(R.id.name);
        name.setText(names);
        email = findViewById(R.id.email);
        email.setText(emails);

        auth = FirebaseAuth.getInstance();
        FirebaseUser user = auth.getCurrentUser();

        home = findViewById(R.id.home);
        home.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                startActivity(new Intent(Profile.this, Restaurant_list.class));
            }
        });
        GoogleSignInOptions gso = new GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
                .requestIdToken(getString(R.string.default_web_client_id))
                .requestEmail()
                .build();

        mGoogleSignInClient = GoogleSignIn.getClient(Profile.this, gso);

        findViewById(R.id.sign_out_button).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                auth.signOut();
                mGoogleSignInClient.signOut();
                finish();
                startActivity(new Intent(Profile.this,User_login.class));
            }
        });
    }

    @Override
    protected void onStart() {
        super.onStart();
        if (auth.getCurrentUser() == null) {
            finish();
            startActivity(new Intent(Profile.this,User_login.class));
        }
    }
}
