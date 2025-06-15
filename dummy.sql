-- CATEGORY Tablosu için Dummy Veri
INSERT INTO `CATEGORY` (`category_id`, `category_name`) VALUES
(1, 'İçecekler'),
(2, 'Kahveler'),
(3, 'Soğuk Kahveler'),
(4, 'Sıcak Kahveler'),
(5, 'Tatlılar'),
(6, 'Sandviçler'),
(7, 'Çaylar'),
(8, 'Gazlı İçecekler'),
(9, 'Meyve Suları'),
(10, 'Pasta Çeşitleri'),
(11, 'Salatalar'),
(12, 'Atıştırmalıklar');

-- CATEGORY_TREE Tablosu için Dummy Veri
INSERT INTO `CATEGORY_TREE` (`parent_category_id`, `sub_category_id`) VALUES
(1, 2),
(1, 7),
(1, 8),
(1, 9),
(2, 3),
(2, 4),
(5, 10),
(5, 12);

-- CUSTOMER Tablosu için Dummy Veri
INSERT INTO `CUSTOMER` (`customer_id`, `customer_name`, `customer_surname`, `customer_phone`, `customer_email`, `customer_nickname`, `customer_password`) VALUES
(1, 'Ayşe', 'Yılmaz', '5321234567', 'ayse.yilmaz@email.com', 'ayseyilmaz', '$2y$10$AG16gi4gOG/XGWeHo4VUDuDlTU04T2lRUJvlHie6blPzD9y5S21Wi'),
(2, 'Mehmet', 'Demir', '5439876543', 'mehmet.demir@email.com', 'mehmetd', '$2y$10$WtqV/62A.bwVio6Uhg6lKOBNQkG2nFwN/BJj4Euq0NJhHzh2pWuXG'),
(3, 'Zeynep', 'Kaya', '5051112233', 'zeynep.kaya@email.com', 'zeynepp', '$2y$10$I76bhWw9PE1PuXwJZZxULuotJdgV6HkyA47yYEc8S1ZaI9zJ0pUMe'),
(4, 'Ali', 'Can', '5543334455', 'ali.can@email.com', 'alican', '$2y$10$pm.LC0Gr.SYC.BdwhAZ2a.opUemAnBhPH67xBI0OnCIH7HcOwgVBu'),
(5, 'Fatma', 'Erkan', '5307778899', 'fatma.erkan@email.com', 'fatma_e', '$2y$10$AkNmZamR3JQBEtWkrigdVOen23I4VRhTYPCxIwioyrKS72IfCICA.');

-- EMPLOYEE Tablosu için Dummy Veri
INSERT INTO `EMPLOYEE` (`employee_id`, `employee_name`, `employee_surname`, `employee_phone`, `employee_email`, `employee_password`, `employee_hire_date`) VALUES
(1, 'Elif', 'Aksoy', '5312223344', 'elif.aksoy@cafe.com', 'sifre123', '2023-01-15'),
(2, 'Burak', 'Aslan', '5415556677', 'burak.aslan@cafe.com', 'parola456', '2022-11-01'),
(3, 'Cem', 'Polat', '5078889900', 'cem.polat@cafe.com', 'gizlisifre', '2024-03-20');


-- PRODUCT Tablosu için Dummy Veri
INSERT INTO `PRODUCT` (`product_id`, `category_id`, `product_description`, `product_img_url`, `product_name`, `product_price`) VALUES
(1, 4, 'Klasik sıcak kahve', 'url_latte.jpg', 'Latte', 50),
(2, 4, 'Yoğun kıvamlı sıcak kahve', 'url_espresso.jpg', 'Espresso', 40),
(3, 3, 'Buzlu karamelli kahve', 'url_iced_caramel.jpg', 'Buzlu Karamel Macchiato', 65),
(4, 7, 'Geleneksel Türk çayı', 'url_turk_cay.jpg', 'Türk Çayı', 25),
(5, 10, 'Çikolatalı ve fındıklı pasta', 'url_cik_pasta.jpg', 'Çikolatalı Fındıklı Pasta', 70),
(6, 4, 'Sıcak çikolata', 'url_hot_choc.jpg', 'Sıcak Çikolata', 55),
(7, 3, 'Soğuk demleme kahve', 'url_cold_brew.jpg', 'Cold Brew', 60),
(8, 2, 'Filtre kahve', 'url_filter_coffee.jpg', 'Filtre Kahve', 45),
(9, 6, 'Hindi fümeli sandviç', 'url_turkey_sandwich.jpg', 'Hindi Fümeli Sandviç', 80),
(10, 5, 'Cheesecake', 'url_cheesecake.jpg', 'Cheesecake', 75),
(11, 3, 'Buzlu latte', 'url_iced_latte.jpg', 'Iced Latte', 55),
(12, 11, 'Yeşil salata', 'url_green_salad.jpg', 'Mevsim Salatası', 60);



-- RECEIPT Tablosu için Dummy Veri
INSERT INTO `RECEIPT` (`receipt_id`, `customer_id`, `employee_id`, `receipt_timestamp`, `receipt_total_amount`) VALUES
(1, 1, 1, '2025-06-11 10:30:00', 115),
(2, 2, 2, '2025-06-11 11:00:00', 90),
(3, 3, 1, '2025-06-11 12:15:00', 135),
(4, 4, 3, '2025-06-11 14:45:00', 80),
(5, 5, 2, '2025-06-11 16:00:00', 130);

-- RECEIPT_PRODUCTS Tablosu için Dummy Veri
INSERT INTO `RECEIPT_PRODUCTS` (`receipt_id`, `product_id`, `product_quantity`, `product_total_amount`) VALUES
(1, 1, 1, 50),  -- Fiş 1: Latte
(1, 5, 1, 70),  -- Fiş 1: Çikolatalı Fındıklı Pasta
(2, 4, 2, 50),  -- Fiş 2: Türk Çayı (2 adet)
(2, 8, 1, 45),  -- Fiş 2: Filtre Kahve
(3, 3, 1, 65),  -- Fiş 3: Buzlu Karamel Macchiato
(3, 10, 1, 75), -- Fiş 3: Cheesecake
(4, 9, 1, 80),  -- Fiş 4: Hindi Fümeli Sandviç
(5, 6, 1, 55),  -- Fiş 5: Sıcak Çikolata
(5, 11, 1, 55), -- Fiş 5: Iced Latte
(5, 12, 1, 60); -- Fiş 5: Mevsim Salatası