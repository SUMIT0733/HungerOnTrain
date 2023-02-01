package com.HOT.star_0733.hottrain;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.net.ConnectivityManager;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.v4.app.DialogFragment;
import android.support.v4.app.FragmentTransaction;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ScrollView;
import android.widget.Toast;
import com.HOT.star_0733.hottrain.dialog.InternetWarningDialog;
import com.google.android.gms.auth.api.signin.GoogleSignIn;
import com.google.android.gms.auth.api.signin.GoogleSignInAccount;
import com.google.android.gms.auth.api.signin.GoogleSignInClient;
import com.google.android.gms.auth.api.signin.GoogleSignInOptions;
import com.google.android.gms.common.api.ApiException;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.auth.AuthCredential;
import com.google.firebase.auth.AuthResult;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;
import com.google.firebase.auth.GoogleAuthProvider;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONArray;
import org.json.JSONObject;

import cc.cloudist.acplibrary.ACProgressConstant;
import cc.cloudist.acplibrary.ACProgressFlower;
import cz.msebera.android.httpclient.Header;

public class User_login extends AppCompatActivity {

    Button sign_in_button;
    GoogleSignInClient signInClient;
    FirebaseAuth auth;
    AsyncHttpClient client;
    ProgressDialog pd;
    SharedPreferences sharedpreferences;
    SharedPreferences.Editor editor;
    ConnectivityManager connectivityManager;
    public static final String MyPREFERENCES = "HOT";

    ACProgressFlower dialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_login);

        //hide the actionbar
        getSupportActionBar().hide();

        auth = FirebaseAuth.getInstance();
        client = new AsyncHttpClient();

        sharedpreferences = getSharedPreferences(MyPREFERENCES, Context.MODE_PRIVATE);
        editor = sharedpreferences.edit();

        dialog = new ACProgressFlower.Builder(User_login.this)
                .direction(ACProgressConstant.DIRECT_CLOCKWISE)
                .themeColor(R.color.grey_700)
                .bgColor(Color.WHITE)
                .textAlpha(1)
                .text("Please wait...")
                .textColor(Color.BLACK)
                .speed(15)
                .bgAlpha(1)
                .fadeColor(Color.WHITE)
                .build();

        pd = new ProgressDialog(User_login.this);
        pd.setIndeterminate(true);
        pd.setCancelable(false);

        GoogleSignInOptions gso = new GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
                .requestIdToken(getString(R.string.default_web_client_id))
                .requestEmail()
                .build();

        signInClient = GoogleSignIn.getClient(User_login.this, gso);

        sign_in_button = findViewById(R.id.sign_in_button);
        sign_in_button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                connectivityManager = ((ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE));
                if(connectivityManager.getActiveNetworkInfo() != null && connectivityManager.getActiveNetworkInfo().isConnected())
                {

                    if (auth.getCurrentUser() == null) {
                        signIn();
                    }else
                        checkUser();
                }
                else
                {
                    Toast.makeText(User_login.this, "No Internet Connection", Toast.LENGTH_SHORT).show();
                    FragmentTransaction ft = getSupportFragmentManager().beginTransaction();
                    DialogFragment dia = new InternetWarningDialog();
                    dia.setCancelable(false);
                    //finish();
                }
            }
        });

    }

    private void signIn() {
        Intent signInIntent = signInClient.getSignInIntent();
        startActivityForResult(signInIntent, 0733);
    }

    @Override
    protected void onRestart() {
        super.onRestart();
        Toast.makeText(this, "restart", Toast.LENGTH_SHORT).show();
    }

    @Override
    protected void onStart() {
        super.onStart();
        //Toast.makeText(this, "Resumed.", Toast.LENGTH_SHORT).show();
        final ConnectivityManager connectivityManager = ((ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE));

        if(connectivityManager.getActiveNetworkInfo() != null && connectivityManager.getActiveNetworkInfo().isConnected())
        {
            if (auth.getCurrentUser() != null) {
                checkUser();
            }
        }
        else
        {
            Toast.makeText(User_login.this, "No Internet Connection", Toast.LENGTH_SHORT).show();
//            FragmentTransaction ft = getSupportFragmentManager().beginTransaction();
//            DialogFragment dia = new InternetWarningDialog();
//            dia.setCancelable(false);
//            dia.show(ft,"internetwarning");
        }
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == 0733) {

            //Getting the GoogleSignIn Task
            Task<GoogleSignInAccount> task = GoogleSignIn.getSignedInAccountFromIntent(data);
            try {
                GoogleSignInAccount account = task.getResult(ApiException.class);

                //authenticating with firebase
                dialog.setTitle("Please wait...");
                dialog.show();
                firebaseAuthWithGoogle(account);

            } catch (ApiException e) {
                Toast.makeText(User_login.this, e.getMessage(), Toast.LENGTH_SHORT).show();
            }
        }
    }

    private void firebaseAuthWithGoogle(GoogleSignInAccount acct) {
        AuthCredential credential = GoogleAuthProvider.getCredential(acct.getIdToken(), null);
//        editor.putString("tocken",acct.getIdToken());
        //Toast.makeText(this, ""+acct.getIdToken(), Toast.LENGTH_SHORT).show();

        //Now using firebase we are signing in the user here
        auth.signInWithCredential(credential)
                .addOnCompleteListener(this, new OnCompleteListener<AuthResult>() {
                    @Override
                    public void onComplete(@NonNull Task<AuthResult> task) {
                        if (task.isSuccessful()) {
                            Log.d("-----------------------", "signInWithCredential:success");
                            //Toast.makeText(User_login.this, "User Signed In", Toast.LENGTH_SHORT).show();
                            insertData();
                        } else {
                            // If sign in fails, display a message to the user.
                            Log.w("---------error-----", "signInWithCredential:failure", task.getException());
                            Toast.makeText(User_login.this, "Authentication failed.",
                                    Toast.LENGTH_SHORT).show();
                            dialog.dismiss();
                        }
                    }
                });
    }

    public void insertData(){
        FirebaseUser user = auth.getCurrentUser();
        Log.d("--------email--------",user.getEmail());
        RequestParams params = new RequestParams();
        params.put("name",user.getDisplayName());
        params.put("email",user.getEmail());
        if(user.getPhotoUrl() != null) {
            params.put("url", user.getPhotoUrl());
        }
        else {
            params.put("url","no Image");
        }
        params.put("tocken",getSharedPreferences("DeviceToken", MODE_PRIVATE).getString("token","213546879415"));
        client.post(CommonUtil.url+"find_user.php",params,new JsonHttpResponseHandler(){
            @Override
            public void onStart() {
                dialog.setTitle("connecting to database..");
            }

            @Override
            public void onFinish() {
                super.onFinish();
                dialog.dismiss();
            }

            @Override
            public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                Log.d("---data---",response.toString());
                try {
                    Log.d("-----response---",response.getString("response"));
                    //Toast.makeText(User_login.this, response.getString("response"), Toast.LENGTH_SHORT).show();
                    String res = response.getString("response");
                    if(res.equals("found") || res.equals("insert success")){
                        JSONArray array = response.getJSONArray("data");
                        JSONObject object = array.getJSONObject(0);
                        editor.putString("name",object.getString("customer_name"));
                        editor.putString("id",object.getString("customer_id"));
                        editor.putString("email",object.getString("customer_email"));
                        editor.commit();
                        checkUser();
                    }
                    else {
                        Toast.makeText(User_login.this, "Error occured.. Try after sometime.. inside", Toast.LENGTH_SHORT).show();
                        dialog.dismiss();
                        if(auth.getCurrentUser() != null){
                            auth.signOut();
                            signInClient.signOut();
                        }
                    }
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                Log.d("----fail----",responseString);
                Toast.makeText(User_login.this, "Error occured.. Try after sometime.. outside\n"+responseString, Toast.LENGTH_SHORT).show();
                dialog.dismiss();
                if(auth.getCurrentUser() != null){
                    auth.signOut();
                    signInClient.signOut();
                }

            }
        });
    }

    public void checkUser() {
        RequestParams params = new RequestParams();
        params.put("email",auth.getCurrentUser().getEmail());
        params.put("tocken",getSharedPreferences("DeviceToken", MODE_PRIVATE).getString("token",""));
        client.post(CommonUtil.url+"check_user.php",params,new JsonHttpResponseHandler(){
            @Override
            public void onStart() {
                dialog.setTitle("Checking user");
                dialog.show();
            }

            @Override
            public void onFinish() {
                dialog.dismiss();
            }

            @Override
            public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                Log.d("---data---",response.toString());
                try {
                    if(response.getString("valid").equals("valid")){
                        dialog.dismiss();
                        finish();
                        startActivity(new Intent(User_login.this,Home.class));
                    }
                    else if(response.getString("valid").equals("block")){
                        Toast.makeText(User_login.this, "This user is blocked by System.Please contact ADMIN.", Toast.LENGTH_SHORT).show();
                        dialog.dismiss();
                        auth.signOut();
                        signInClient.signOut();
                    }
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                Toast.makeText(User_login.this, responseString, Toast.LENGTH_SHORT).show();
                Log.d("----------Check------",responseString);
                dialog.dismiss();
                if(auth.getCurrentUser() != null){
                    auth.signOut();
                    signInClient.signOut();
                }
            }
        });

    }
}

