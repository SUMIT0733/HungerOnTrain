package com.HOT.star_0733.hottrain.model;

public class Train_list_model {
    String name,arrival_time,depart_time;

    public Train_list_model(String name, String arrival_time, String depart_time) {
        this.name = name;
        this.arrival_time = arrival_time;
        this.depart_time = depart_time;
    }

    public String getName() {
        return name;
    }

    public String getArrival_time() {
        return arrival_time;
    }

    public String getDepart_time() {
        return depart_time;
    }
}
