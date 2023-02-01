package com.HOT.star_0733.hottrain;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Color;
import android.location.Address;
import android.location.Geocoder;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.design.widget.BottomSheetBehavior;
import android.support.design.widget.FloatingActionButton;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.CardView;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.RatingBar;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.HOT.star_0733.hottrain.model.OrderModel;
import com.google.android.gms.maps.model.LatLng;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;
import com.squareup.picasso.Picasso;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import cc.cloudist.acplibrary.ACProgressConstant;
import cc.cloudist.acplibrary.ACProgressFlower;
import cz.msebera.android.httpclient.Header;
import de.hdodenhof.circleimageview.CircleImageView;
import pl.droidsonroids.gif.GifImageView;

import static android.support.design.widget.BottomSheetBehavior.STATE_COLLAPSED;
import static android.support.design.widget.BottomSheetBehavior.STATE_EXPANDED;

public class ViewOrder extends AppCompatActivity {

      TextView text,order_id,restaurant_name,restaurant_address,restaurant_contact;
      TextView delivery_name,delivery_contact,delivery_address,delivery_city,delivery_time,amount;
      TextView delivery_person_info,delivery_person_name,delivery_person_contact,track_text,map_track;
      LinearLayout delivery_person_content;
      TextView text_review,rating_done;
      CardView review_card;
      RatingBar ratingbar;
      ListView listView;
      String order,data=" ",order_status,delivery_person_id;
      RelativeLayout llBottomSheet;
      AsyncHttpClient client;
      ACProgressFlower dialog;
      ArrayList<OrderModel> list;
      GifImageView process_gif;
      String contact;
      Double to_lat,to_lng,from_lat,from_lng;
      private BottomSheetBehavior bottomSheetBehavior;
      boolean rating;
      ImageView up_arrow;
      LinearLayout offer_content;
      TextView promocode,original_amt,discount_amt,delivery_amt,final_amt,discout_text;
      String station_name = "";
      CircleImageView delivery_img;

      @Override
      protected void onCreate(Bundle savedInstanceState) {
            super.onCreate(savedInstanceState);
            setContentView(R.layout.activity_view_order);

            getWindow().setFlags(WindowManager.LayoutParams.FLAG_LAYOUT_NO_LIMITS,WindowManager.LayoutParams.FLAG_LAYOUT_NO_LIMITS);

            initView();
            getIntents();
            initComponent();
            getDetail();

            llBottomSheet.setEnabled(false);
            text.setText("Order : "+order.split(" ")[2].trim());

            rating_done.setOnClickListener(new View.OnClickListener() {
                  @Override
                  public void onClick(View view) {
                        int rat = (int)ratingbar.getRating();
                        if(rat == 0)
                              Toast.makeText(ViewOrder.this, "Please select star.", Toast.LENGTH_SHORT).show();
                        else {
                              RequestParams params = new RequestParams();
                              params.put("order",order.split(" ")[2].trim());
                              params.put("ratting",rat);
                              client.post(CommonUtil.url+"feedback.php",params,new JsonHttpResponseHandler(){
                                    @Override
                                    public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                                          Log.d("---respnonce---",response.toString());
                                          Toast.makeText(ViewOrder.this, "Submit success", Toast.LENGTH_SHORT).show();
                                          getDetail();
                                    }

                                    @Override
                                    public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                                          Toast.makeText(ViewOrder.this, "Something went wrong.", Toast.LENGTH_SHORT).show();
                                    }

                                    @Override
                                    public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                                          Toast.makeText(ViewOrder.this, "Something went wrong.", Toast.LENGTH_SHORT).show();
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
            });
      }

      private void getDetail() {
            RequestParams params = new RequestParams();
            params.put("id",order.split(" ")[2].trim());
            client.post(CommonUtil.url+"getorderdetail.php",params,new JsonHttpResponseHandler(){
                  @Override
                  public void onSuccess(int statusCode, Header[] headers, JSONObject response) {
                        super.onSuccess(statusCode, headers, response);
                        //dialog.dismiss();
                        Log.d("---detail---",response.toString());
                        JSONArray data = null;
                        try {
                              data = response.getJSONArray("data");
                              JSONObject object = data.getJSONObject(0);

                              restaurant_name.setText(object.getString("restaurant_name"));
                              restaurant_address.setText(object.getString("restaurant_address")+","+object.getString("city_name")+" - "+object.getString("restaurant_pincode"));
                            from_lat = Double.valueOf(object.getString("lat"));
                            from_lng = Double.valueOf(object.getString("lng"));
                              //restaurant_contact.setText(object.getString("contact_no"));
                              contact = object.getString("contact_no");
                              restaurant_contact.setOnClickListener(new View.OnClickListener() {
                                    @Override
                                    public void onClick(View view) {
                                          Intent intent = new Intent(Intent.ACTION_DIAL);
                                          intent.setData(Uri.parse("tel: "+contact));
                                          startActivity(intent);
                                    }
                              });
                              if(!object.getString("order_rating").equals("0")){
                                    rating = true;
                              }
                              delivery_name.setText(object.getString("end_customer_name"));
                              delivery_contact.setText(object.getString("end_customer_contact"));
                              delivery_address.setText(object.getString("delivery_address"));
                              delivery_city.setText(object.getString("delivery_station")+"  ##  "+object.getString("order_time"));
                              station_name = object.getString("delivery_station");
                              geoData(station_name);

                              delivery_time.setText(object.getString("create_date"));
                              order_status = object.getString("order_status");

                            if(object.getString("offer_code").equals("NOCODE")){
                                promocode.setVisibility(View.GONE);
                                discount_amt.setVisibility(View.GONE);
                                discout_text.setVisibility(View.GONE);
                                original_amt.setText("\u20b9 "+object.getString("order_amount"));
                                int tmp = Integer.parseInt(object.getString("order_amount")) + 10;
                                final_amt.setText("\u20b9 "+tmp);

                            }else {
                                promocode.setText(object.getString("offer_code"));
                                original_amt.setText("\u20b9 "+object.getString("order_amount"));
                                int ori = Integer.parseInt(object.getString("order_amount"));
                                int effect = Integer.parseInt(object.getString("effect_amount"));
                                discount_amt.setText("- \u20b9 "+String.valueOf(ori - effect));
                                final_amt.setText("\u20b9 "+String.valueOf(effect + 10));
                            }

                              switch (order_status){
                                    case "0":
                                          track_text.setText("Restaurant has not accepted your order yet.");
                                          process_gif.setImageResource(R.drawable.pending);
                                          break;
                                    case "1":
                                          track_text.setText("Your order is accepted and is being prepared.");
                                          process_gif.setImageResource(R.drawable.processing);
                                          break;
                                    case "2":
                                          track_text.setText("Your food is prepared.");
                                          process_gif.setImageResource(R.drawable.done);
                                          break;
                                    case "3":
                                          track_text.setText("Your food is on the way.");
                                          process_gif.setImageResource(R.drawable.way_1);
                                          break;
                                    case "4":
                                          track_text.setText("Food delivered.");
                                          process_gif.setImageResource(R.drawable.delivered);
                                          llBottomSheet.setVisibility(View.GONE);

                                          if(!rating) {
                                                review_card.setVisibility(View.VISIBLE);
                                                text_review.setVisibility(View.VISIBLE);
                                          }else {
                                                review_card.setVisibility(View.VISIBLE);
                                                text_review.setVisibility(View.VISIBLE);
                                                ratingbar.setIsIndicator(true);
                                                ratingbar.setRating(Float.parseFloat(object.getString("order_rating")));
                                                rating_done.setVisibility(View.GONE);
                                          }
                                          break;
                                    case "-1":
                                          track_text.setText("Your order is rejected.");
                                          process_gif.setImageResource(R.drawable.reject);
                                          delivery_person_info.setVisibility(View.GONE);
                                          delivery_person_contact.setVisibility(View.GONE);
                                          break;
                              }

                              delivery_person_id = object.getString("delivery_person_id");
                              switch (delivery_person_id){
                                    case "0":
                                          delivery_person_info.setText("Once Delivery person assigned to your order, we will show details");
                                          delivery_person_info.setTextSize(15);
                                          delivery_person_content.setVisibility(View.GONE);
                                          break;
                                    default:
                                          delivery_person_info.setText("Delivery person detail");
                                          delivery_person_info.setTextSize(20);
                                          delivery_person_content.setVisibility(View.VISIBLE);
                                          break;
                              }
                              JSONArray cart = object.getJSONArray("cart");
                              for(int i=0;i<cart.length();i++){
                                    JSONObject cart1 = cart.getJSONObject(i);

                                    list.add(new OrderModel(cart1.getString("food_item_name"),
                                                cart1.getString("unit"),
                                                "\u20b9  "+cart1.getString("subtotal"),
                                                Integer.parseInt(cart1.getString("Veg"))));
                              }

                              OrderAdapter adapter = new OrderAdapter(ViewOrder.this,list);
                              listView.setAdapter(adapter);
                              amount.setText("Total :    \u20b9 "+object.getString("order_amount"));

                        } catch (JSONException e) {
                              e.printStackTrace();
                        }

                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                        //dialog.dismiss();
                        Toast.makeText(ViewOrder.this, "Something went wrong, Please try again!", Toast.LENGTH_SHORT).show();
                        finish();
                  }

                  @Override
                  public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                        //dialog.dismiss();
                        Toast.makeText(ViewOrder.this, "Something went wrong, Please try again!", Toast.LENGTH_SHORT).show();
                        finish();
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

    private void geoData(String station_name) {
        if(Geocoder.isPresent()){
            try {
                String location = station_name+" railway station";
                Geocoder gc = new Geocoder(this);
                List<Address> addresses= gc.getFromLocationName(location, 1); // get the found Address Objects

                List<LatLng> ll = new ArrayList<>(addresses.size()); // A list to save the coordinates if they are available
                for(Address a : addresses){
                    if(a.hasLatitude() && a.hasLongitude()){
                        ll.add(new LatLng(a.getLatitude(), a.getLongitude()));
                    }
                }
                to_lat = ll.get(0).latitude;
                to_lng = ll.get(0).longitude;
                Log.d("location",""+ll.get(0).latitude+"// "+ll.get(0).longitude);
            } catch (IOException e) {
                // handle the exception
            }
        }
      }

    private void getIntents() {
            order = getIntent().getStringExtra("order");
      }

      private void initView() {
            list = new ArrayList<>();
            text = findViewById(R.id.order_id);
            client = new AsyncHttpClient();
            llBottomSheet = findViewById(R.id.bottom_sheet);
            process_gif = findViewById(R.id.process_gif);

            dialog = new ACProgressFlower.Builder(ViewOrder.this)
                        .direction(ACProgressConstant.DIRECT_CLOCKWISE)
                        .themeColor(R.color.grey_700)
                        .text("Loading...")
                        .textColor(Color.BLACK)
                        .bgColor(Color.WHITE)
                        .textAlpha(1)
                        .speed(15)
                        .bgAlpha(1)
                        .fadeColor(Color.WHITE)
                        .build();

            restaurant_name = findViewById(R.id.restaurant_name);
            restaurant_contact = findViewById(R.id.restaurant_contact);
            restaurant_address = findViewById(R.id.restaurant_address);

            delivery_name = findViewById(R.id.delivery_name);
            delivery_contact = findViewById(R.id.delivery_contact);
            delivery_address = findViewById(R.id.delivery_address);
            delivery_city = findViewById(R.id.delivery_city);
            delivery_time = findViewById(R.id.delivery_time);
            amount = findViewById(R.id.amount);

            listView = findViewById(R.id.listview);

            delivery_person_info = findViewById(R.id.delivery_person_info);
            delivery_person_name = findViewById(R.id.delivery_person_name);
            delivery_person_contact = findViewById(R.id.delivery_person_contact);
            delivery_person_content = findViewById(R.id.delivery_person_content);
            map_track = findViewById(R.id.map_track);
            delivery_img = findViewById(R.id.delivery_img);

            track_text = findViewById(R.id.track_text);

            text_review = findViewById(R.id.text_review);
            review_card = findViewById(R.id.review_card);
            ratingbar = findViewById(R.id.ratingbar);
            rating_done = findViewById(R.id.rating_done);

          offer_content = findViewById(R.id.offer_content);
          original_amt = findViewById(R.id.original_amt);
          discount_amt = findViewById(R.id.discount_amt);
          delivery_amt = findViewById(R.id.delivery_amt);
          final_amt = findViewById(R.id.final_amt);
          promocode = findViewById(R.id.promocode);
          discout_text = findViewById(R.id.discout_text);

          up_arrow = findViewById(R.id.up_arrow);
      }

      private void initComponent() {
            bottomSheetBehavior = BottomSheetBehavior.from(llBottomSheet);
            bottomSheetBehavior.setHideable(false);
            bottomSheetBehavior.setState(BottomSheetBehavior.STATE_COLLAPSED);
            bottomSheetBehavior.setBottomSheetCallback(new BottomSheetBehavior.BottomSheetCallback() {
                  @Override
                  public void onStateChanged(@NonNull View bottomSheet, int newState) {
                        if(newState == STATE_EXPANDED){
                            up_arrow.setImageResource(R.drawable.down_arrow);
                              RequestParams params = new RequestParams();
                              params.put("id",order.split(" ")[2].trim());
                              client.post(CommonUtil.url+"getdeliverydetail.php",params,new JsonHttpResponseHandler(){
                                    @Override
                                    public void onSuccess(int statusCode, Header[] headers, final JSONObject response) {
                                          super.onSuccess(statusCode, headers, response);
                                          //dialog.dismiss();
                                          Log.d("---detail---",response.toString());
                                          try {

                                                if(!response.getString("order_rating").equals("0")){
                                                      rating = true;
                                                }
                                                order_status = response.getString("order_status");
                                                switch (order_status){
                                                      case "0":
                                                            track_text.setText("Restaurant has not accepted your order yet.");
                                                            process_gif.setImageResource(R.drawable.pending);
                                                            break;
                                                      case "1":
                                                            track_text.setText("Your order is accepted and is being prepared.");
                                                            process_gif.setImageResource(R.drawable.processing);
                                                            break;
                                                      case "2":
                                                            track_text.setText("Your food is prepared.");
                                                            process_gif.setImageResource(R.drawable.done);
                                                            break;
                                                      case "3":
                                                            track_text.setText("Your food is on the way.");
                                                            process_gif.setImageResource(R.drawable.way_1);
                                                            break;
                                                      case "4":
                                                            track_text.setText("Food delivered.");
                                                            process_gif.setImageResource(R.drawable.delivered);
                                                            llBottomSheet.setVisibility(View.GONE);

                                                            if(!rating) {
                                                                  review_card.setVisibility(View.VISIBLE);
                                                                  text_review.setVisibility(View.VISIBLE);
                                                            }else {
                                                                  review_card.setVisibility(View.VISIBLE);
                                                                  text_review.setVisibility(View.VISIBLE);
                                                                  ratingbar.setIsIndicator(true);
                                                                  ratingbar.setRating(Float.parseFloat(response.getString("order_rating")));
                                                                  rating_done.setVisibility(View.GONE);
                                                            }

                                                            break;
                                                      case "-1":
                                                            track_text.setText("Your order is rejected.");
                                                            process_gif.setImageResource(R.drawable.reject);
                                                            break;
                                                }

                                                String delivery = response.getString("delivery");
                                                if(delivery.equals("Delivery")){
                                                      delivery_person_info.setText("Delivery person detail");
                                                      delivery_person_info.setTextSize(20);
                                                      delivery_person_content.setVisibility(View.VISIBLE);
                                                      map_track.setOnClickListener(new View.OnClickListener() {
                                                            @Override
                                                            public void onClick(View view) {
                                                                  try {
                                                                        startActivity(new Intent(ViewOrder.this,MapsActivity.class).putExtra("delivery_id",response.getString("id"))
                                                                        .putExtra("to_lat",to_lat)
                                                                        .putExtra("to_lng",to_lng)
                                                                        .putExtra("from_lat",from_lat)
                                                                        .putExtra("from_lng",from_lng));
                                                                  } catch (JSONException e) {
                                                                        e.printStackTrace();
                                                                  }
                                                            }
                                                      });
                                                      JSONArray data = response.getJSONArray("data");
                                                      JSONObject object = data.getJSONObject(0);

                                                      delivery_person_name.setText(object.getString("name"));
                                                    Picasso.get().load(object.getString("profile_url")).into(delivery_img);
                                                      delivery_person_contact.setText("CALL");
                                                      final String delivery_contact = object.getString("contact_no");
                                                      delivery_person_contact.setOnClickListener(new View.OnClickListener() {
                                                            @Override
                                                            public void onClick(View view) {
                                                                  Intent intent = new Intent(Intent.ACTION_DIAL);
                                                                  intent.setData(Uri.parse("tel: "+delivery_contact));
                                                                  startActivity(intent);
                                                            }
                                                      });

                                                }else {
                                                      delivery_person_info.setText("Once Delivery person assigned to your order, we will show details");
                                                      delivery_person_info.setTextSize(15);
                                                      delivery_person_content.setVisibility(View.GONE);
                                                }

                                          } catch (JSONException e) {
                                                e.printStackTrace();
                                          }

                                    }

                                    @Override
                                    public void onFailure(int statusCode, Header[] headers, Throwable throwable, JSONObject errorResponse) {
                                          super.onFailure(statusCode, headers, throwable, errorResponse);
                                          //dialog.dismiss();
                                          Toast.makeText(ViewOrder.this, "Something went wrong, Please try again!", Toast.LENGTH_SHORT).show();
                                          finish();
                                    }

                                    @Override
                                    public void onFailure(int statusCode, Header[] headers, String responseString, Throwable throwable) {
                                          super.onFailure(statusCode, headers, responseString, throwable);
                                          //dialog.dismiss();
                                          Toast.makeText(ViewOrder.this, "Something went wrong, Please try again!", Toast.LENGTH_SHORT).show();
                                          finish();
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
                        }else if(newState == STATE_COLLAPSED){
                            up_arrow.setImageResource(R.drawable.top_arrow);
                        }
                  }

                  @Override
                  public void onSlide(@NonNull View bottomSheet, float slideOffset) { }

            });


            ((FloatingActionButton) findViewById(R.id.fab_directions)).setOnClickListener(new View.OnClickListener() {
                  @Override
                  public void onClick(View v) {
                        bottomSheetBehavior.setState(BottomSheetBehavior.STATE_COLLAPSED);
                        try {
                              Intent intent = new Intent(Intent.ACTION_DIAL);
                              intent.setData(Uri.parse("tel: "+contact));
                              startActivity(intent);
                        } catch (Exception e) {
                        }
                  }
            });
      }

      public class OrderAdapter extends ArrayAdapter<OrderModel>{

            Activity context;
            ArrayList<OrderModel> list;
            public OrderAdapter(@NonNull Activity context, @NonNull ArrayList<OrderModel> list) {
                  super(context, R.layout.order_item_row, list);
                  this.context = context;
                  this.list = list;
            }

            @NonNull
            @Override
            public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {
                  convertView = context.getLayoutInflater().inflate(R.layout.order_item_row,null,true);

                  OrderModel model = list.get(position);
                  ImageView food_type = convertView.findViewById(R.id.food_type);
                  TextView food_name = convertView.findViewById(R.id.food_name);
                  TextView unit = convertView.findViewById(R.id.unit);
                  TextView total = convertView.findViewById(R.id.total);

                  food_name.setText(model.getName());
                  unit.setText(model.getUnit());
                  total.setText(model.getTotal());
                  if (model.getVeg() == 1) {
                        food_type.setImageResource(R.drawable.veg);
                  } else {
                        food_type.setImageResource(R.drawable.nonveg);
                  }
                  return convertView;
            }
      }

      @Override
      public void onBackPressed() {
            finish();
      }
}
