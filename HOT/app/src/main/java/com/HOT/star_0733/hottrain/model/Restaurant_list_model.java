package com.HOT.star_0733.hottrain.model;

import android.os.Parcel;
import android.os.Parcelable;

public class Restaurant_list_model implements Parcelable {
    public  int restaurant_id,avg_price;
    public String restaurant_name,email,restaurant_address,contact,website,url,cuisine,pincode,city,state;
    public float rating;

      public String getCity() {
            return city;
      }

      public String getState() {
            return state;
      }

      public Restaurant_list_model(int restaurant_id, String pincode, String restaurant_name, String email, String restaurant_address, String contact, String website, String url, String cuisine, float rating, String city, String state) {
            this.restaurant_id = restaurant_id;
            this.pincode = pincode;
            this.restaurant_name = restaurant_name;
            this.email = email;
            this.restaurant_address = restaurant_address;
            this.contact = contact;

            this.website = website;
            this.url = url;
            this.cuisine = cuisine;
            this.rating = rating;
            this.city = city;
            this.state = state;
      }

      public int getRestaurant_id() {
            return restaurant_id;
      }

      public String getPincode() {
            return pincode;
      }

      public float getRating() {
            return rating;
      }

      public int getAvg_price() {
            return avg_price;
      }

      public String getRestaurant_name() {
            return restaurant_name;
      }

      public String getEmail() {
            return email;
      }

      public String getRestaurant_address() {
            return restaurant_address;
      }

      public String getContact() {
            return contact;
      }

      public String getWebsite() {
            return website;
      }

      public String getUrl() {
            return url;
      }

      public String getCuisine() {
            return cuisine;
      }

      @Override
      public int describeContents() {
            return 0;
      }

      @Override
      public void writeToParcel(Parcel dest, int flags) {
            dest.writeInt(this.restaurant_id);
            dest.writeInt(this.avg_price);
            dest.writeString(this.restaurant_name);
            dest.writeString(this.email);
            dest.writeString(this.restaurant_address);
            dest.writeString(this.contact);
            dest.writeString(this.website);
            dest.writeString(this.url);
            dest.writeString(this.cuisine);
            dest.writeString(this.pincode);
            dest.writeString(this.city);
            dest.writeString(this.state);
            dest.writeFloat(this.rating);
      }

      protected Restaurant_list_model(Parcel in) {
            this.restaurant_id = in.readInt();
            this.avg_price = in.readInt();
            this.restaurant_name = in.readString();
            this.email = in.readString();
            this.restaurant_address = in.readString();
            this.contact = in.readString();
            this.website = in.readString();
            this.url = in.readString();
            this.cuisine = in.readString();
            this.pincode = in.readString();
            this.city = in.readString();
            this.state = in.readString();
            this.rating = in.readFloat();
      }

      public static final Creator<Restaurant_list_model> CREATOR = new Creator<Restaurant_list_model>() {
            @Override
            public Restaurant_list_model createFromParcel(Parcel source) {
                  return new Restaurant_list_model(source);
            }

            @Override
            public Restaurant_list_model[] newArray(int size) {
                  return new Restaurant_list_model[size];
            }
      };
}
