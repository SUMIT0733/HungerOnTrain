package com.HOT.star_0733.hottrain.model;

public class HistoryModel {
      public String name,price,order,time,effect;

      public String getName() {
            return name;
      }

      public String getPrice() {
            return price;
      }

      public String getOrder() {
            return order;
      }

      public String getTime() {
            return time;
      }

    public String getEffect() {
        return effect;
    }

    public HistoryModel(String name, String price, String order, String time, String effect) {

            this.name = name;
            this.price = price;
            this.order = order;
            this.time = time;
            this.effect = effect;
      }
}
