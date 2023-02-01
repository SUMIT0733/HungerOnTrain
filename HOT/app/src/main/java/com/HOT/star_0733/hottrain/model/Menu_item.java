package com.HOT.star_0733.hottrain.model;

import java.io.Serializable;

public class Menu_item implements Serializable{
    public int food_id,rest_id,price,veg;
    public String name,ingerdients,cuisine;


    public Menu_item(int food_id, int rest_id, String cuisine, int price, int veg, String name, String ingerdients) {
        this.food_id = food_id;
        this.rest_id = rest_id;
        this.cuisine = cuisine;
        this.price = price;
        this.veg = veg;
        this.name = name;
        this.ingerdients = ingerdients;
    }

    public int getFood_id() {
        return food_id;
    }

    public int getRest_id() {
        return rest_id;
    }

    public String getCuisine() {
        return cuisine;
    }

    public int getPrice() {
        return price;
    }

    public int getVeg() {
        return veg;
    }

    public String getName() {
        return name;
    }

    public String getIngerdients() {
        return ingerdients;
    }
}
