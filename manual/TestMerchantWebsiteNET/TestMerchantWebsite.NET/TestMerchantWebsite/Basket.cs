using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using TestMerchantWebsite.CNPMerchantWebService;

namespace TestMerchantWebsite
{
    public class Basket
    {
        public static List<GoodsItem> getBasket()
        {
            List<GoodsItem> basket = (List<GoodsItem>)HttpContext.Current.Session["basket"];
            if (basket == null)
            {
                basket = new List<GoodsItem>(0);
                HttpContext.Current.Session["basket"] = basket;
            }
            return basket;
        }

        public List<GoodsItem> selectBasketContents()
        {
            List<GoodsItem> basket = (List<GoodsItem>)HttpContext.Current.Session["basket"];
            if (basket == null)
            {
                basket = new List<GoodsItem>(0);
                HttpContext.Current.Session["basket"] = basket;
            }
            return basket;
        }

        public void addBasketContents(GoodsItem item)
        {
            getBasket().Add(item);
        }

        public void updateBasketContents(GoodsItem item)
        {
            List<GoodsItem> basket = getBasket();
            for (int i = 0; i < basket.Count; i++)
            {
                if (basket[i].merchantsGoodsID.Equals(item.merchantsGoodsID))
                {
                    basket.Remove(basket[i]);
                    basket.Add(item);
                    break;
                }
            }
        }

        public void deleteBasketContents(GoodsItem item)
        {
            List<GoodsItem> basket = getBasket();
            for (int i = 0; i < basket.Count; i++)
            {
                if (basket[i].merchantsGoodsID.Equals(item.merchantsGoodsID))
                {
                    basket.Remove(basket[i]);
                    break;
                }
            }
        }

        /// <summary>
        /// Total amount of goods in the basket
        /// </summary>
        /// <returns>Total amount of goods in the basket</returns>
        public static int getTotalAmount()
        {
            List<GoodsItem> goods = Basket.getBasket();
            int total = 0;
            foreach (GoodsItem goodsItem in goods)
            {
                total += int.Parse(goodsItem.amount);
            }
            return total;
        }
    }
}