using System.ComponentModel.DataAnnotations;

namespace ProductCatalogApi.Dtos
{
     public class ProductCreateUpdateDto
     {
          [Required(ErrorMessage = "Name is required")]
          [StringLength(100, ErrorMessage = "Name cannot exceed 100 characters")]
          public string Name { get; set; } = string.Empty;

          [Required(ErrorMessage = "Category is required")]
          [StringLength(50, ErrorMessage = "Category cannot exceed 50 characters")]
          public string Category { get; set; } = string.Empty;

          [Range(0.01, 1000000, ErrorMessage = "Price must be between 0.01 and 1,000,000")]
          public decimal Price { get; set; }

          [Range(0, int.MaxValue, ErrorMessage = "Stock quantity cannot be negative")]
          public int StockQuantity { get; set; }
     }
}
