package com.HOT.star_0733.hottrain.model;

public class OfferModel {
    public int offer_id,unit,upto,min,usage;
    public String offer_name,offer_desc,offer_code;
    public int PercentageOrNot;

    public OfferModel(int offer_id, int unit, int upto, int min, int usage, String offer_name, String offer_desc, String offer_code, int percentageOrNot) {
        this.offer_id = offer_id;
        this.unit = unit;
        this.upto = upto;
        this.min = min;
        this.usage = usage;
        this.offer_name = offer_name;
        this.offer_desc = offer_desc;
        this.offer_code = offer_code;
        PercentageOrNot = percentageOrNot;
    }

    public int getOffer_id() {
        return offer_id;
    }

    public int getUnit() {
        return unit;
    }

    public int getUpto() {
        return upto;
    }

    public int getMin() {
        return min;
    }

    public int getUsage() {
        return usage;
    }

    public String getOffer_name() {
        return offer_name;
    }

    public String getOffer_desc() {
        return offer_desc;
    }

    public String getOffer_code() {
        return offer_code;
    }

    public int isPercentageOrNot() {
        return PercentageOrNot;
    }
}
