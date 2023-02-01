package com.HOT.star_0733.hottrain;

import android.app.Activity;
import android.app.DatePickerDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.support.design.widget.TextInputEditText;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.Html;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.HOT.star_0733.hottrain.R;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;
import java.util.TimeZone;

import cc.cloudist.acplibrary.ACProgressConstant;
import cc.cloudist.acplibrary.ACProgressFlower;
import cz.msebera.android.httpclient.Header;
import instamojo.library.InstamojoPay;
import instamojo.library.InstapayListener;

public class CheckOut extends AppCompatActivity implements View.OnClickListener{
    ActionBar actionBar;

    TextView contact_detail,delivery_detail,delivery_time,delivery_date,time,instruction_text;
    TextInputEditText contact_name,contact_number,train_name,description;
    Button pick_date,pick_time,payment;
    EditText coach,seat,platfrom;
    String html_str=" ";
    int final_amount = 0,tmp_total = 0;

    LinearLayout contact_content,station_content,instruction_content;
    SharedPreferences preferences,sharedPreferences;
    SharedPreferences.Editor editor;

    TextView offer_text,promocode,original_amt,discount_amt,delivery_amt,final_amt;
    LinearLayout offer_content;
    Intent intent;
    String total,times;
      String user_contact;
    Calendar c;
      String names,emails,id,rest_id,current_time;
    FirebaseUser user;

      ACProgressFlower dialog;
      AsyncHttpClient client;
      RequestParams params;

    int flag_contact=1,flag_delivery=0,flag_instruction=0,mHour,mMinute,mYear,mMonth,mDay;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_check_out);

        actionBar = getSupportActionBar();
        intent = getIntent();
        total = intent.getStringExtra("total");
        final_amount = Integer.parseInt(total.substring(2).trim());
        rest_id = intent.getStringExtra("rest_id");

        c = Calendar.getInstance(Locale.getDefault());
        Date currentLocalTime = c.getTime();
        DateFormat date = new SimpleDateFormat("HH:mm");
        date.setTimeZone(TimeZone.getDefault());
        current_time = date.format(currentLocalTime);

        getPref();
        setScreen();
        initView();

        train_name.setText(preferences.getString("train_name","No Record"));
    }

    public void initView() {
          user = FirebaseAuth.getInstance().getCurrentUser();
        preferences = getSharedPreferences("train", Context.MODE_PRIVATE);
        editor = preferences.edit();
        contact_detail = findViewById(R.id.contact_detail);
        contact_detail.setOnClickListener(this);
        delivery_detail = findViewById(R.id.delivery_detail);
        delivery_detail.setOnClickListener(this);
        instruction_text = findViewById(R.id.instruction_text);
        instruction_text.setOnClickListener(this);

        contact_content = findViewById(R.id.contact_content);
        station_content = findViewById(R.id.station_content);
        instruction_content = findViewById(R.id.instruction_content);
        contact_name = findViewById(R.id.contact_name);
        contact_name.setText(user.getDisplayName());
        contact_number = findViewById(R.id.contact_number);
        description = findViewById(R.id.description);
        description.setHint("Enter instruction for restaurant.");

        delivery_date = findViewById(R.id.delivery_date);
        delivery_time = findViewById(R.id.delivery_time);

        pick_date = findViewById(R.id.pick_date);
        pick_date.setOnClickListener(this);

        pick_time = findViewById(R.id.pick_time);
        pick_time.setOnClickListener(this);

        offer_text = findViewById(R.id.offer_text);
        offer_text.setOnClickListener(this);
        offer_content = findViewById(R.id.offer_content);
        original_amt = findViewById(R.id.original_amt);
        discount_amt = findViewById(R.id.discount_amt);
        delivery_amt = findViewById(R.id.delivery_amt);
        final_amt = findViewById(R.id.final_amt);
        promocode = findViewById(R.id.promocode);

        train_name = findViewById(R.id.train_name);
        coach = findViewById(R.id.coach);
        seat = findViewById(R.id.seat);
        time = findViewById(R.id.time);
        times = preferences.getString("time","10:00");
        time.setText("Estimated arrival  time : "+preferences.getString("time","10:00") +" at "+preferences.getString("station","abcd"));

        payment = findViewById(R.id.payment);
        final_amount = Integer.parseInt(total.substring(2).trim()) + 10;
        payment.setText("Proceed to pay amount : \u20b9 "+final_amount);
        payment.setOnClickListener(this);

        client = new AsyncHttpClient();
          dialog = new ACProgressFlower.Builder(CheckOut.this)
                      .direction(ACProgressConstant.DIRECT_CLOCKWISE)
                      .themeColor(R.color.grey_700)
                      .bgColor(Color.WHITE)
                      .textAlpha(1)
                      .bgAlpha(1)
                      .text("Please wait...")
                      .textColor(Color.BLACK)
                      .speed(15)
                      .fadeColor(Color.WHITE)
                      .build();
    }

    public void setScreen() {
        actionBar.setHomeAsUpIndicator(R.drawable.back);
        actionBar.setDisplayHomeAsUpEnabled(true);
        actionBar.setHomeButtonEnabled(true);
        actionBar.setElevation(25);
        actionBar.setTitle("Check out");
        getWindow().setStatusBarColor(getResources().getColor(R.color.red_500));
    }

      public void getPref() {
            sharedPreferences = getSharedPreferences("HOT", Context.MODE_PRIVATE);

            names = sharedPreferences.getString("name", "john");
            emails = sharedPreferences.getString("email", "abcd@abc.com");
            id = sharedPreferences.getString("id", "100");
      }

      @Override
    public void onClick(View view) {
        switch (view.getId()) {
              case R.id.contact_detail:
                    if (flag_contact == 0) {
                          contact_content.setVisibility(View.VISIBLE);
                          flag_contact = 1;
                    } else {
                          contact_content.setVisibility(View.GONE);
                          flag_contact = 0;
                    }
                    break;
              case R.id.delivery_detail:
                    if (flag_delivery == 0) {
                          station_content.setVisibility(View.VISIBLE);
                          flag_delivery = 1;
                    } else {
                          station_content.setVisibility(View.GONE);
                          flag_delivery = 0;
                    }
                    break;
              case R.id.instruction_text:
                    if (flag_instruction == 0) {
                          instruction_content.setVisibility(View.VISIBLE);
                          flag_instruction = 1;
                    } else {
                          instruction_content.setVisibility(View.GONE);
                          flag_instruction = 0;
                    }
                    break;

            case R.id.offer_text:
                startActivity(new Intent(CheckOut.this,Offer.class).putExtra("total",total.substring(2).trim()));
                break;
            case R.id.payment:

                    if ((toMinute(preferences.getString("time","10:00")) - toMinute(current_time) >= 60)){
                          validation();
                    }
                    else {
                    Toast.makeText(CheckOut.this, "You have to place order atleast 1 hour before arriving at the station.", Toast.LENGTH_SHORT).show();
                          //deleteCart(id);
                          validation();
                    }
                    break;

            case R.id.pick_date:
                c = Calendar.getInstance();
                mYear = c.get(Calendar.YEAR);
                mMonth = c.get(Calendar.MONTH);
                mDay = c.get(Calendar.DAY_OF_MONTH);

                DatePickerDialog datePickerDialog = new DatePickerDialog(this,R.style.Dialog_Theme,
                        new DatePickerDialog.OnDateSetListener() {
                            @Override
                            public void onDateSet(DatePicker view, int year,
                                                  int monthOfYear, int dayOfMonth) {
                                  String month="";
                                  if((monthOfYear + 1) <= 9){
                                        month = "0"+ (monthOfYear + 1);
                                  }else{
                                        month = ""+(monthOfYear + 1);
                                  }
                                  String day = "";
                                  if((dayOfMonth) <= 9){
                                        day = "0"+(dayOfMonth);
                                  }
                                  else {
                                        day = ""+(dayOfMonth);
                                  }
                                delivery_date.setText(year+"-"+month+"-"+day);

                            }
                        }, mYear, mMonth, mDay);
                  datePickerDialog.getDatePicker().setMinDate(System.currentTimeMillis() - 1000);
                datePickerDialog.show();
        }

    }

      public void validation() {
            params = new RequestParams();

            user_contact  = contact_number.getText().toString().trim();
            if(user_contact.equals(null) || user_contact.length() < 10){
                  Toast.makeText(this, "Please enter valid contact number.", Toast.LENGTH_SHORT).show();
            }else if(coach.getText().toString().trim().equals("")){
                  Toast.makeText(this, "Please enter valid coach number.", Toast.LENGTH_SHORT).show();
            }else if(seat.getText().toString().trim().equals("")){
                  Toast.makeText(this, "Please enter valid seat number.", Toast.LENGTH_SHORT).show();
            } else if(delivery_date.getText().toString().trim().equals("")){
                  Toast.makeText(this, "Please select delivery date.", Toast.LENGTH_SHORT).show();
            } else {
                  RequestParams params = new RequestParams();
                  params.put("rest_id",rest_id);
                  client.post(CommonUtil.url+"getRastaurantStatus.php",params,new JsonHttpResponseHandler(){
                        @Override
                        public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                              try {
                                    if(response.getString("status").equals("1")){
                                          callInstamojoPay(user.getEmail(),user_contact,String.valueOf(final_amount),"For online food order",user.getDisplayName());

                                    }else {
                                          AlertDialog.Builder builder = new AlertDialog.Builder(CheckOut.this,R.style.Dialog_Theme)
                                                      .setMessage("Restaurant has been closed.")
                                                      .setTitle("Error")
                                                      .setPositiveButton("Ok", new DialogInterface.OnClickListener() {
                                                            @Override
                                                            public void onClick(DialogInterface dialogInterface, int i) {
                                                                  deleteCart(id);
                                                            }
                                                      });
                                          builder.setCancelable(false);
                                          builder.show();
                                    }
                              } catch (JSONException e) {
                                    e.printStackTrace();
                              }
                        }

                        @Override
                        public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                              Toast.makeText(CheckOut.this, responseString, Toast.LENGTH_SHORT).show();
                        }

                        @Override
                        public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {

                        }

                        @Override
                        public void onStart() {
                              dialog.show();
                        }

                        @Override
                        public void onFinish() {
                              dialog.dismiss();
                        }
                  });
      }
    }

    @Override
    protected void onResume() {
        super.onResume();
        SharedPreferences preferences = getSharedPreferences("OFFER",MODE_PRIVATE);
        boolean offer_apply = preferences.getBoolean("offer_apply",false);
        if(offer_apply){
            offer_content.setVisibility(View.VISIBLE);
            promocode.setText(preferences.getString("code"," "));
            original_amt.setText(total);
            discount_amt.setText("- \u20b9 "+(Integer.parseInt(total.substring(2).trim()) - Integer.parseInt(preferences.getString("effect"," "))));
            int effect = Integer.parseInt(preferences.getString("effect"," "));
            final_amount = effect + 10;
            payment.setText("Proceed to pay amount : \u20b9 "+final_amount);
            final_amt.setText("\u20b9 "+(effect + 10));

        }else {
            offer_content.setVisibility(View.GONE);
        }
    }

    public int toMinute(String time){

            int int_time = Integer.parseInt(time.split(":")[0])*60 + Integer.parseInt(time.split(":")[1]);
            return int_time;
      }

      public void doPayment(String payment_id) {
            params.put("user_id",id);
            params.put("rest_id", rest_id);

            String user_name = contact_name.getText().toString().trim();
            if(user_name.equals("")){
                  params.put("user_name",names);
            }else {
                  params.put("user_name",user_name);
            }
            String user_contact = contact_number.getText().toString().trim();
            params.put("user_contact",user_contact);
            String address = preferences.getString("train_name","No Record")+" , "+coach.getText()+" - "+seat.getText();
            params.put("address",address);
            params.put("station",preferences.getString("station","No Record"));
            String instr = description.getText().toString().trim();
            if(instr.equals(""))
                  params.put("instruction","No Instruction");
            else
                  params.put("instruction",instr);

            params.put("time",delivery_date.getText()+" "+times+":00");
            params.put("amount",total.substring(2).trim());
            params.put("offer_id",getSharedPreferences("OFFER",MODE_PRIVATE).getString("offer_id","0"));

            boolean offer_apply = getSharedPreferences("OFFER",MODE_PRIVATE).getBoolean("offer_apply",false);
            if(!offer_apply){
                params.put("effect_amount",total.substring(2).trim());
            }else
            {
                params.put("effect_amount",getSharedPreferences("OFFER",MODE_PRIVATE).getString("effect","0"));
            }
            params.put("payment_id",payment_id);
            params.put("payment_status",1);
            client.post(CommonUtil.url+"order.php",params,new JsonHttpResponseHandler(){
                  @Override
                  public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                        super.onSuccess(statusCode, headers, response);
                        dialog.dismiss();

                        try {
                              if(response.getString("delete_reponce").equals("Success") && response.getString("cart_responce").equals("Success") && response.getString("responce").equals("Success")) {
                                    Log.d("------cart_info-----", response.toString());
                                    String order = response.getString("order_id");
                                    finish();
                                    startActivity(new Intent(CheckOut.this, Order_summery.class)
                                                .putExtra("data", response.toString()));
                              }
                              else {
                                    Toast.makeText(CheckOut.this, "Error ! Something went wrong.", Toast.LENGTH_SHORT).show();
                              }
                        } catch (JSONException e) {
                              e.printStackTrace();
                        }
                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                        super.onFailure(statusCode, headers, throwable, errorResponse);
                        dialog.dismiss();
                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                        super.onFailure(statusCode, headers, responseString, throwable);
                        dialog.dismiss();
                        Log.d("----error----",responseString);
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

      @Override
    public boolean onSupportNavigateUp() {
        finish();
        return true;
    }

      public void deleteCart(String id) {
            RequestParams params = new RequestParams();
            params.put("id",id);
            client.post(CommonUtil.url+"deletecarts.php",params,new JsonHttpResponseHandler(){
                  @Override
                  public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                        Log.d("---delete---",response.toString());
                        try {
                              if(response.getString("responce").equals("Success"))
                              finish();
                              Intent intent = new Intent(CheckOut.this, Home.class);
                              intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                              startActivity(intent);
                        } catch (JSONException e) {
                              e.printStackTrace();
                        }

                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                        super.onFailure(statusCode, headers, throwable, errorResponse);
                        //finish();
                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                        super.onFailure(statusCode, headers, responseString, throwable);
                        //finish();
                  }

                  @Override
                  public void onStart() {
                        super.onStart();
                        dialog.show();
                  }

                  @Override
                  public void onFinish() {
                        super.onFinish();
                        //finish();
                        dialog.dismiss();
                  }
            });
      }

      private void callInstamojoPay(String email, String phone, String amount, String purpose, String buyername) {
            final Activity activity = this;
            InstamojoPay instamojoPay = new InstamojoPay();
            IntentFilter filter = new IntentFilter("ai.devsupport.instamojo");
            registerReceiver(instamojoPay, filter);
            JSONObject pay = new JSONObject();
            try {
                  pay.put("email", email);
                  pay.put("phone", phone);
                  pay.put("purpose", purpose);
                  pay.put("amount", amount);
                  pay.put("name", buyername);
                  pay.put("send_sms", false);
                  pay.put("send_email", true);
            } catch (JSONException e) {
                  e.printStackTrace();
            }
            initListener();
            instamojoPay.start(activity, pay, listener);
      }

      InstapayListener listener;

      private void initListener() {
            listener = new InstapayListener() {
                  @Override
                  public void onSuccess(String response) {
                        Log.d("-----instamojo-----",response);
                        doPayment((response.split(":")[1]).split("=")[1]);
                  }

                  @Override
                  public void onFailure(int code, String reason) {
                        Toast.makeText(getApplicationContext(), "Payment Failed: " + reason, Toast.LENGTH_LONG)
                                    .show();
                  }
            };
      }
}
