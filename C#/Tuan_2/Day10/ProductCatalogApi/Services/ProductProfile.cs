using AutoMapper;
using ProductCatalogApi.Dtos;
using ProductCatalogApi.Models;

namespace ProductCatalogApi.Services
{
     public class ProductProfile : Profile
     {
          public ProductProfile()
          {
               CreateMap<Product, ProductDto>();
               CreateMap<ProductCreateUpdateDto, Product>();
          }
     }
}