package com.HOT.star_0733.hot_delivery.model;

public class OrderDetail {
    public String order,name,time;

    public OrderDetail(String order, String name, String time) {
        this.order = order;
        this.name = name;
        this.time = time;
    }

    public String getOrder() {
        return order;
    }

    public String getName() {
        return name;
    }

    public String getTime() {
        return time;
    }
}
