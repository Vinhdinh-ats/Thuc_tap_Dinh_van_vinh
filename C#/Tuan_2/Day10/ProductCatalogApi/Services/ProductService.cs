using AutoMapper;
using Microsoft.EntityFrameworkCore;
using ProductCatalogApi.Data;
using ProductCatalogApi.Dtos;
using ProductCatalogApi.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace ProductCatalogApi.Services
{
     public class ProductService
     {
          private readonly AppDbContext _context;
          private readonly IMapper _mapper;

          public ProductService(AppDbContext context, IMapper mapper)
          {
               _context = context;
               _mapper = mapper;
          }

          // CRUD: Create
          public async Task<ProductDto> CreateProductAsync(ProductCreateUpdateDto dto)
          {
               var product = _mapper.Map<Product>(dto);
               _context.Products.Add(product);
               await _context.SaveChangesAsync();
               return _mapper.Map<ProductDto>(product);
          }

          // CRUD: Read (Get by ID)
          public async Task<ProductDto> GetProductByIdAsync(int id)
          {
               var product = await _context.Products.FindAsync(id);
               if (product == null)
                    throw new KeyNotFoundException("Product not found.");
               return _mapper.Map<ProductDto>(product);
          }

          // CRUD: Read (Get all with filter and pagination)
          public async Task<List<ProductDto>> GetProductsAsync(string? name = null, string? category = null,
              decimal? minPrice = null, decimal? maxPrice = null, int page = 1, int pageSize = 10)
          {
               var query = _context.Products.AsQueryable();

               if (!string.IsNullOrEmpty(name))
                    query = query.Where(p => p.Name != null && p.Name.Contains(name, StringComparison.OrdinalIgnoreCase));

               if (!string.IsNullOrEmpty(category))
                    query = query.Where(p => p.Category == category);
               if (minPrice.HasValue)
                    query = query.Where(p => p.Price >= minPrice.Value);
               if (maxPrice.HasValue)
                    query = query.Where(p => p.Price <= maxPrice.Value);

               query = query.Skip((page - 1) * pageSize).Take(pageSize);

               var products = await query.ToListAsync();
               return _mapper.Map<List<ProductDto>>(products);
          }

          // CRUD: Update
          public async Task<ProductDto> UpdateProductAsync(int id, ProductCreateUpdateDto dto)
          {
               var product = await _context.Products.FindAsync(id);
               if (product == null)
                    throw new KeyNotFoundException("Product not found.");

               _mapper.Map(dto, product);
               await _context.SaveChangesAsync();
               return _mapper.Map<ProductDto>(product);
          }

          // CRUD: Delete
          public async Task DeleteProductAsync(int id)
          {
               var product = await _context.Products.FindAsync(id);
               if (product == null)
                    throw new KeyNotFoundException("Product not found.");

               _context.Products.Remove(product);
               await _context.SaveChangesAsync();
          }

          // Check stock before selling
          public async Task<bool> CheckStockAsync(int id, int quantity)
          {
               var product = await _context.Products.FindAsync(id);
               if (product == null)
                    throw new KeyNotFoundException("Product not found.");

               return product.StockQuantity >= quantity;
          }

          // Update stock after selling
          public async Task UpdateStockAsync(int id, int quantity)
          {
               var product = await _context.Products.FindAsync(id);
               if (product == null)
                    throw new KeyNotFoundException("Product not found.");

               if (product.StockQuantity < quantity)
                    throw new InvalidOperationException("Not enough stock.");

               product.StockQuantity -= quantity;
               await _context.SaveChangesAsync();
          }

          // Report total inventory value
          public async Task<decimal> GetTotalInventoryValueAsync()
          {
               return await _context.Products.SumAsync(p => p.Price * p.StockQuantity);
          }
     }
}