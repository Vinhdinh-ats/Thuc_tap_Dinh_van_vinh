using Microsoft.AspNetCore.Mvc;
using ProductCatalogApi.Dtos;
using ProductCatalogApi.Services;
using System.Threading.Tasks;

namespace ProductCatalogApi.Controllers
{
     [Route("api/[controller]")]
     [ApiController]
     public class ProductsController : ControllerBase
     {
          private readonly ProductService _productService;

          public ProductsController(ProductService productService)
          {
               _productService = productService;
          }

          // POST: api/products
          [HttpPost]
          public async Task<ActionResult<ProductDto>> CreateProduct([FromBody] ProductCreateUpdateDto dto)
          {
               var product = await _productService.CreateProductAsync(dto);
               return CreatedAtAction(nameof(GetProduct), new { id = product.Id }, product);
          }

          // GET: api/products/5
          [HttpGet("{id}")]
          public async Task<ActionResult<ProductDto>> GetProduct(int id)
          {
               try
               {
                    var product = await _productService.GetProductByIdAsync(id);
                    return Ok(product);
               }
               catch (KeyNotFoundException)
               {
                    return NotFound();
               }
          }

          // GET: api/products?name=phone&category=electronics&minPrice=100&maxPrice=1000&page=1&pageSize=10
          [HttpGet]
          public async Task<ActionResult> GetProducts(
    [FromQuery] string? name = null,
    [FromQuery] string? category = null,
    [FromQuery] decimal? minPrice = null,
    [FromQuery] decimal? maxPrice = null,
    [FromQuery] int page = 1,
    [FromQuery] int pageSize = 10)
          {
               var products = await _productService.GetProductsAsync(name, category, minPrice, maxPrice, page, pageSize);
               return Ok(products);
          }

          // PUT: api/products/5
          [HttpPut("{id}")]
          public async Task<ActionResult<ProductDto>> UpdateProduct(int id, [FromBody] ProductCreateUpdateDto dto)
          {
               try
               {
                    var product = await _productService.UpdateProductAsync(id, dto);
                    return Ok(product);
               }
               catch (KeyNotFoundException)
               {
                    return NotFound();
               }
          }

          // DELETE: api/products/5
          [HttpDelete("{id}")]
          public async Task<IActionResult> DeleteProduct(int id)
          {
               try
               {
                    await _productService.DeleteProductAsync(id);
                    return NoContent();
               }
               catch (KeyNotFoundException)
               {
                    return NotFound();
               }
          }

          // POST: api/products/5/sell
          [HttpPost("{id}/sell")]
          public async Task<IActionResult> SellProduct(int id, [FromQuery] int quantity)
          {
               try
               {
                    var isAvailable = await _productService.CheckStockAsync(id, quantity);
                    if (!isAvailable)
                         return BadRequest("Not enough stock.");

                    await _productService.UpdateStockAsync(id, quantity);
                    return Ok();
               }
               catch (KeyNotFoundException)
               {
                    return NotFound();
               }
               catch (InvalidOperationException ex)
               {
                    return BadRequest(ex.Message);
               }
          }

          // GET: api/products/total-inventory-value
          [HttpGet("total-inventory-value")]
          public async Task<ActionResult<decimal>> GetTotalInventoryValue()
          {
               var totalValue = await _productService.GetTotalInventoryValueAsync();
               return Ok(totalValue);
          }
     }
}