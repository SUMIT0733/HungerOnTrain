package com.HOT.star_0733.hottrain.model;

public class City {
    String city,state;
    int city_id,state_id;

    public City(String city,String state,int city_id,int state_id)
    {
        this.city = city;
        this.city_id = city_id;
        this.state = state;
        this.state_id = state_id;
    }

    public String getState() {
        return state;
    }

    public int getCity_id() {
        return city_id;
    }

    public int getState_id() {
        return state_id;
    }

    public String getCity() {
        return city;
    }
}


