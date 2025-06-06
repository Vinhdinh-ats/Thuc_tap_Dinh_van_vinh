using Microsoft.EntityFrameworkCore;
using ProductCatalogApi.Models;

namespace ProductCatalogApi.Data
{
     public class AppDbContext : DbContext
     {
          public DbSet<Product> Products { get; set; }

          public AppDbContext(DbContextOptions<AppDbContext> options) : base(options)
          {
          }
     }
}