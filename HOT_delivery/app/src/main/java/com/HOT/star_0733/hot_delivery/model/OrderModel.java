package com.HOT.star_0733.hot_delivery.model;

public class OrderModel {
      public String name,unit,total;
      public int veg;

      public OrderModel(String name, String unit, String total, int veg) {
            this.name = name;
            this.unit = unit;
            this.total = total;
            this.veg = veg;
      }

      public int getVeg() {
            return veg;
      }

      public String getName() {
            return name;
      }

      public String getUnit() {
            return unit;
      }

      public String getTotal() {
            return total;
      }
}
