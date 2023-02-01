package com.HOT.star_0733.hot_delivery;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.util.Patterns;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import cc.cloudist.acplibrary.ACProgressConstant;
import cc.cloudist.acplibrary.ACProgressFlower;
import cz.msebera.android.httpclient.Header;

public class Login extends AppCompatActivity {

      Button payment,login;
      EditText email,pass;
      TextView register;
      AsyncHttpClient client;
      ACProgressFlower dialog;
      SharedPreferences preferences;
      SharedPreferences.Editor editor;

      @Override
      protected void onCreate(Bundle savedInstanceState) {
            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_login);

            preferences = getSharedPreferences(CommonUtil.Pref, Context.MODE_PRIVATE);
            editor = preferences.edit();
            initView();

            register.setOnClickListener(new View.OnClickListener() {
                  @Override
                  public void onClick(View view) {
                        finish();
                        startActivity(new Intent(Login.this,Register.class));
                  }
            });

            login.setOnClickListener(new View.OnClickListener() {
                  @Override
                  public void onClick(View view) {
                        doValidation();
                  }
            });
      }

      private void doValidation() {
            String str_email = email.getText().toString().trim();
            String str_pass = pass.getText().toString().trim();

            if(str_email.isEmpty() && str_pass.isEmpty() ){
                  Toast.makeText(this, "Please fill all the details.", Toast.LENGTH_SHORT).show();
            }else if (str_email.isEmpty()){
                  Toast.makeText(this, "Please fill email id", Toast.LENGTH_SHORT).show();

            }else if (!Patterns.EMAIL_ADDRESS.matcher(str_email).matches()){
                  Toast.makeText(this, "Please fill valid email id", Toast.LENGTH_SHORT).show();

            }else if ( str_pass.isEmpty()){
                  Toast.makeText(this, "Please fill the password", Toast.LENGTH_SHORT).show();
            }else {
                  doLogin(str_email,str_pass);
            }
      }

      private void doLogin(String str_email, String str_pass) {
            RequestParams params = new RequestParams();
            params.put("id",str_email);
            params.put("pass",str_pass);
            params.put("token",getSharedPreferences("DeviceToken",MODE_PRIVATE).getString("token"," "));
            client.post(CommonUtil.url+"delivery_login.php",params, new JsonHttpResponseHandler(){
                  @Override
                  public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                        super.onSuccess(statusCode, headers, response);
                        dialog.dismiss();
                        Log.d("-------svnasvkja-----",response.toString());
                        try {
                              if(response.getString("responce").equals("Success")){
                                    Toast.makeText(Login.this, "Login success", Toast.LENGTH_SHORT).show();
                                    JSONArray data = response.getJSONArray("data");
                                    JSONObject object = data.getJSONObject(0);
                                    editor.putString("id",object.getString("delivery_person_id"));
                                    editor.putString("name",object.getString("name"));
                                    editor.putString("email",object.getString("email"));
                                    editor.putString("contact",object.getString("contact_no"));
                                    editor.putBoolean("login",true);
                                    editor.commit();
                                    finish();
                                    startActivity(new Intent(Login.this,Home.class));
                              }
                              else if(response.getString("responce").equals("not")){
                                    Toast.makeText(Login.this, "Account not verified yet.", Toast.LENGTH_SHORT).show();
                              }else {
                                    Toast.makeText(Login.this, "Invalid credentials", Toast.LENGTH_SHORT).show();
                              }
                        } catch (JSONException e) {
                              e.printStackTrace();
                        }

                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                        super.onFailure(statusCode, headers, throwable, errorResponse);
                        dialog.dismiss();
                        Toast.makeText(Login.this, ""+errorResponse.toString(), Toast.LENGTH_SHORT).show();
                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                        super.onFailure(statusCode, headers, responseString, throwable);
                        dialog.dismiss();
                        Toast.makeText(Login.this, ""+responseString, Toast.LENGTH_SHORT).show();

                  }

                  @Override
                  public void onStart() {
                        super.onStart();
                        dialog.show();
                  }

                  @Override
                  public void onFinish() {
                        super.onFinish();
                        dialog.dismiss();

                  }
            });
      }

      private void initView() {
            email = findViewById(R.id.email);
            pass = findViewById(R.id.pass);
            login = findViewById(R.id.login);
            register = findViewById(R.id.register);
            client = new AsyncHttpClient();

            dialog = new ACProgressFlower.Builder(Login.this)
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
      }
}
