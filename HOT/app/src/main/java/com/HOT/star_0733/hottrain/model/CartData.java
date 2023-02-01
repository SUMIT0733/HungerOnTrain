package com.HOT.star_0733.hottrain.model;

import android.os.Parcel;
import android.os.Parcelable;

public class CartData  {
    public int cart_id,food_id,price,unit,Veg;
    public String food_item_name,cuisine;

    public CartData(int cart_id, int food_id, int price, int unit, int veg, String food_item_name,String cuisine) {
        this.cart_id = cart_id;
        this.food_id = food_id;
        this.price = price;
        this.unit = unit;
        this.Veg = veg;
        this.cuisine = cuisine;
        this.food_item_name = food_item_name;
    }

    public int getCart_id() {
        return cart_id;
    }

    public int getFood_id() {
        return food_id;
    }

    public int getPrice() {
        return price;
    }

    public int getUnit() {
        return unit;
    }

    public int getVeg() {
        return Veg;
    }

    public String getFood_item_name() {
        return food_item_name;
    }

    public String getCuisine() {
        return cuisine;
    }

}
