import {__} from "@wordpress/i18n";
import Config_Values from "../../es6/config-values";

const schemaTypeOptions = [
	{
		id: 'Article',
		label: __('Article', 'wds'),
		icon: 'sui-icon-page'
	},
	{
		id: 'Event',
		label: __('Event', 'wds'),
		icon: 'sui-icon-calendar'
	},
	{
		id: 'Product',
		label: __('Product', 'wds'),
		icon: 'sui-icon-element-checkbox'
	},
	{
		id: 'FAQPage',
		label: __('FAQ Page', 'wds'),
		icon: 'sui-icon-question'
	},
	{
		id: 'HowTo',
		label: __('How To', 'wds'),
		icon: 'sui-icon-list-bullet'
	},
	{
		id: 'LocalBusiness',
		label: __('Local Business', 'wds'),
		icon: 'sui-icon-home',
		options: [
			{
				id: 'AnimalShelter',
				label: __('Animal Shelter', 'wds'),
				icon: 'wds-schema-icon-animal-shelter',
			},
			{
				id: 'AutomotiveBusiness',
				label: __('Automotive Business', 'wds'),
				icon: 'wds-schema-icon-automotive',
				options: [
					{
						id: 'AutoBodyShop',
						label: __('Auto Body Shop', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'AutoDealer',
						label: __('Auto Dealer', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'AutoPartsStore',
						label: __('Auto Parts Store', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'AutoRental',
						label: __('Auto Rental', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'AutoRepair',
						label: __('Auto Repair', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'AutoWash',
						label: __('Auto Wash', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'GasStation',
						label: __('Gas Station', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'MotorcycleDealer',
						label: __('Motorcycle Dealer', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'MotorcycleRepair',
						label: __('Motorcycle Repair', 'wds'),
						icon: 'wds-schema-icon-',
					}
				]
			},
			{
				id: 'ChildCare',
				label: __('Child Care', 'wds'),
				icon: 'wds-schema-icon-child-care',
			},
			{
				id: 'DryCleaningOrLaundry',
				label: __('Dry Cleaning Or Laundry', 'wds'),
				icon: 'wds-schema-icon-dry-cleaning',
			},
			{
				id: 'EmergencyService',
				label: __('Emergency Service', 'wds'),
				icon: 'wds-schema-icon-emergency-services',
				options: [
					{
						id: 'FireStation',
						label: __('Fire Station', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'Hospital',
						label: __('Hospital', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'PoliceStation',
						label: __('Police Station', 'wds'),
						icon: 'wds-schema-icon-',
					}
				]
			},
			{
				id: 'EmploymentAgency',
				label: __('Employment Agency', 'wds'),
				icon: 'wds-schema-icon-employment',
			},
			{
				id: 'EntertainmentBusiness',
				label: __('Entertainment Business', 'wds'),
				icon: 'wds-schema-icon-entertainment',
				options: [
					{
						id: 'AdultEntertainment',
						label: __('Adult Entertainment', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'AmusementPark',
						label: __('Amusement Park', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'ArtGallery',
						label: __('Art Gallery', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'Casino',
						label: __('Casino', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'ComedyClub',
						label: __('Comedy Club', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'MovieTheater',
						label: __('Movie Theater', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'NightClub',
						label: __('Night Club', 'wds'),
						icon: 'wds-schema-icon-',
					}
				]
			},
			{
				id: 'FinancialService',
				label: __('Financial Service', 'wds'),
				icon: 'wds-schema-icon-financial',
				options: [
					{
						id: 'AccountingService',
						label: __('Accounting Service', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'AutomatedTeller',
						label: __('Automated Teller', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'BankOrCreditUnion',
						label: __('Bank Or Credit Union', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'InsuranceAgency',
						label: __('Insurance Agency', 'wds'),
						icon: 'wds-schema-icon-',
					},
				]
			},
			{
				id: 'FoodEstablishment',
				label: __('Food Establishment', 'wds'),
				icon: 'wds-schema-icon-food-business',
				options: [
					{
						id: 'Bakery',
						label: __('Bakery', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'BarOrPub',
						label: __('Bar Or Pub', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'Brewery',
						label: __('Brewery', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'CafeOrCoffeeShop',
						label: __('Cafe Or Coffee Shop', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'Distillery',
						label: __('Distillery', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'FastFoodRestaurant',
						label: __('Fast Food Restaurant', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'IceCreamShop',
						label: __('Ice Cream Shop', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'Restaurant',
						label: __('Restaurant', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'Winery',
						label: __('Winery', 'wds'),
						icon: 'wds-schema-icon-',
					}
				]
			},
			{
				id: 'GovernmentOffice',
				label: __('Government Office', 'wds'),
				icon: 'wds-schema-icon-government',
				options: [
					{
						id: 'PostOffice',
						label: __('Post Office', 'wds'),
						icon: 'wds-schema-icon-',
					}
				]
			},
			{
				id: 'HealthAndBeautyBusiness',
				label: __('Health And Beauty Business', 'wds'),
				icon: 'wds-schema-icon-health',
				options: [
					{
						id: 'BeautySalon',
						label: __('Beauty Salon', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'DaySpa',
						label: __('Day Spa', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'HairSalon',
						label: __('Hair Salon', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'HealthClub',
						label: __('Health Club', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'NailSalon',
						label: __('Nail Salon', 'wds'),
						icon: 'wds-schema-icon-',
					},
					{
						id: 'TattooParlor',
						label: __('Tattoo Parlor', 'wds'),
						icon: 'wds-schema-icon-',
					}
				]
			},
			{
				id: "HomeAndConstructionBusiness",
				label: "Home And Construction Business",
				icon: "wds-schema-icon-home-and-construction",
				options: [
					{
						id: "Electrician",
						label: "Electrician",
						icon: "wds-schema-icon-"
					}, {
						id: "GeneralContractor",
						label: "General Contractor",
						icon: "wds-schema-icon-"
					}, {
						id: "HVACBusiness",
						label: "HVACBusiness",
						icon: "wds-schema-icon-"
					}, {
						id: "HousePainter",
						label: "House Painter",
						icon: "wds-schema-icon-"
					}, {
						id: "Locksmith",
						label: "Locksmith",
						icon: "wds-schema-icon-"
					}, {
						id: "MovingCompany",
						label: "Moving Company",
						icon: "wds-schema-icon-"
					}, {
						id: "Plumber",
						label: "Plumber",
						icon: "wds-schema-icon-"
					}, {
						id: "RoofingContractor",
						label: "Roofing Contractor",
						icon: "wds-schema-icon-"
					}]
			}, {
				id: "InternetCafe",
				label: "Internet Cafe",
				icon: "wds-schema-icon-internet-cafe"
			}, {
				id: "LegalService",
				label: "Legal Service",
				icon: "wds-schema-icon-legal-services",
				options: [
					{
						id: "Attorney",
						label: "Attorney",
						icon: "wds-schema-icon-"
					},
					{
						id: "Notary",
						label: "Notary",
						icon: "wds-schema-icon-"
					}
				]
			}, {
				id: "Library",
				label: "Library",
				icon: "wds-schema-icon-library"
			}, {
				id: "LodgingBusiness",
				label: "Lodging Business",
				icon: "wds-schema-icon-lodging",
				options: [
					{
						id: "BedAndBreakfast",
						label: "Bed And Breakfast",
						icon: "wds-schema-icon-"
					}, {
						id: "Campground",
						label: "Campground",
						icon: "wds-schema-icon-"
					}, {id: "Hostel", label: "Hostel", icon: "wds-schema-icon-"}, {
						id: "Hotel",
						label: "Hotel",
						icon: "wds-schema-icon-"
					}, {id: "Motel", label: "Motel", icon: "wds-schema-icon-"}, {
						id: "Resort",
						label: "Resort",
						icon: "wds-schema-icon-"
					}
				]
			}, {
				id: "MedicalBusiness",
				label: "Medical Business",
				icon: "wds-schema-icon-medical-business",
				options: [
					{
						id: "CommunityHealth",
						label: "Community Health",
						icon: "wds-schema-icon-"
					}, {
						id: "Dentist",
						label: "Dentist",
						icon: "wds-schema-icon-"
					}, {
						id: "Dermatology",
						label: "Dermatology",
						icon: "wds-schema-icon-"
					}, {
						id: "DietNutrition",
						label: "Diet Nutrition",
						icon: "wds-schema-icon-"
					}, {
						id: "Emergency",
						label: "Emergency",
						icon: "wds-schema-icon-"
					}, {
						id: "Geriatric",
						label: "Geriatric",
						icon: "wds-schema-icon-"
					}, {
						id: "Gynecologic",
						label: "Gynecologic",
						icon: "wds-schema-icon-"
					}, {
						id: "MedicalClinic",
						label: "Medical Clinic",
						icon: "wds-schema-icon-"
					}, {
						id: "Midwifery",
						label: "Midwifery",
						icon: "wds-schema-icon-"
					}, {
						id: "Nursing",
						label: "Nursing",
						icon: "wds-schema-icon-"
					}, {
						id: "Obstetric",
						label: "Obstetric",
						icon: "wds-schema-icon-"
					}, {
						id: "Oncologic",
						label: "Oncologic",
						icon: "wds-schema-icon-"
					}, {
						id: "Optician",
						label: "Optician",
						icon: "wds-schema-icon-"
					}, {
						id: "Optometric",
						label: "Optometric",
						icon: "wds-schema-icon-"
					}, {
						id: "Otolaryngologic",
						label: "Otolaryngologic",
						icon: "wds-schema-icon-"
					}, {
						id: "Pediatric",
						label: "Pediatric",
						icon: "wds-schema-icon-"
					}, {
						id: "Pharmacy",
						label: "Pharmacy",
						icon: "wds-schema-icon-"
					}, {
						id: "Physician",
						label: "Physician",
						icon: "wds-schema-icon-"
					}, {
						id: "Physiotherapy",
						label: "Physiotherapy",
						icon: "wds-schema-icon-"
					}, {
						id: "PlasticSurgery",
						label: "Plastic Surgery",
						icon: "wds-schema-icon-"
					}, {
						id: "Podiatric",
						label: "Podiatric",
						icon: "wds-schema-icon-"
					}, {
						id: "PrimaryCare",
						label: "Primary Care",
						icon: "wds-schema-icon-"
					}, {
						id: "Psychiatric",
						label: "Psychiatric",
						icon: "wds-schema-icon-"
					},
					{
						id: "PublicHealth",
						label: "Public Health",
						icon: "wds-schema-icon-"
					}
				]
			}, {
				id: "ProfessionalService",
				label: "Professional Service",
				icon: "wds-schema-icon-professional-service"
			}, {
				id: "RadioStation",
				label: "Radio Station",
				icon: "wds-schema-icon-radio-station"
			}, {
				id: "RealEstateAgent",
				label: "Real Estate Agent",
				icon: "wds-schema-icon-real-estate-agent"
			}, {
				id: "RecyclingCenter",
				label: "Recycling Center",
				icon: "wds-schema-icon-recycling-center"
			}, {
				id: "SelfStorage",
				label: "Self Storage",
				icon: "wds-schema-icon-self-storage"
			}, {
				id: "ShoppingCenter",
				label: "Shopping Center",
				icon: "wds-schema-icon-shopping-center"
			}, {
				id: "SportsActivityLocation",
				label: "Sports Activity Location",
				icon: "wds-schema-icon-sports-activity-location",
				options: [
					{
						id: "BowlingAlley",
						label: "Bowling Alley",
						icon: "wds-schema-icon-"
					}, {
						id: "ExerciseGym",
						label: "Exercise Gym",
						icon: "wds-schema-icon-"
					}, {
						id: "GolfCourse",
						label: "Golf Course",
						icon: "wds-schema-icon-"
					}, {
						id: "HealthClub",
						label: "Health Club",
						icon: "wds-schema-icon-"
					}, {
						id: "PublicSwimmingPool",
						label: "Public Swimming Pool",
						icon: "wds-schema-icon-"
					}, {
						id: "SkiResort",
						label: "Ski Resort",
						icon: "wds-schema-icon-"
					}, {
						id: "SportsClub",
						label: "Sports Club",
						icon: "wds-schema-icon-"
					}, {
						id: "StadiumOrArena",
						label: "Stadium Or Arena",
						icon: "wds-schema-icon-"
					}, {
						id: "TennisComplex",
						label: "Tennis Complex",
						icon: "wds-schema-icon-"
					}]
			}, {
				id: "Store",
				label: "Store",
				icon: "wds-schema-icon-store",
				options: [
					{
						id: "AutoPartsStore",
						label: "Auto Parts Store",
						icon: "wds-schema-icon-"
					}, {id: "BikeStore", label: "Bike Store", icon: "wds-schema-icon-"}, {
						id: "BookStore",
						label: "Book Store",
						icon: "wds-schema-icon-"
					}, {
						id: "ClothingStore",
						label: "Clothing Store",
						icon: "wds-schema-icon-"
					}, {
						id: "ComputerStore",
						label: "Computer Store",
						icon: "wds-schema-icon-"
					}, {
						id: "ConvenienceStore",
						label: "Convenience Store",
						icon: "wds-schema-icon-"
					}, {
						id: "DepartmentStore",
						label: "Department Store",
						icon: "wds-schema-icon-"
					}, {
						id: "ElectronicsStore",
						label: "Electronics Store",
						icon: "wds-schema-icon-"
					}, {id: "Florist", label: "Florist", icon: "wds-schema-icon-"}, {
						id: "FurnitureStore",
						label: "Furniture Store",
						icon: "wds-schema-icon-"
					}, {
						id: "GardenStore",
						label: "Garden Store",
						icon: "wds-schema-icon-"
					}, {
						id: "GroceryStore",
						label: "Grocery Store",
						icon: "wds-schema-icon-"
					}, {
						id: "HardwareStore",
						label: "Hardware Store",
						icon: "wds-schema-icon-"
					}, {
						id: "HobbyShop",
						label: "Hobby Shop",
						icon: "wds-schema-icon-"
					}, {
						id: "HomeGoodsStore",
						label: "Home Goods Store",
						icon: "wds-schema-icon-"
					}, {
						id: "JewelryStore",
						label: "Jewelry Store",
						icon: "wds-schema-icon-"
					}, {
						id: "LiquorStore",
						label: "Liquor Store",
						icon: "wds-schema-icon-"
					}, {
						id: "MensClothingStore",
						label: "Mens Clothing Store",
						icon: "wds-schema-icon-"
					}, {
						id: "MobilePhoneStore",
						label: "Mobile Phone Store",
						icon: "wds-schema-icon-"
					}, {
						id: "MovieRentalStore",
						label: "Movie Rental Store",
						icon: "wds-schema-icon-"
					}, {
						id: "MusicStore",
						label: "Music Store",
						icon: "wds-schema-icon-"
					}, {
						id: "OfficeEquipmentStore",
						label: "Office Equipment Store",
						icon: "wds-schema-icon-"
					}, {
						id: "OutletStore",
						label: "Outlet Store",
						icon: "wds-schema-icon-"
					}, {id: "PawnShop", label: "Pawn Shop", icon: "wds-schema-icon-"}, {
						id: "PetStore",
						label: "Pet Store",
						icon: "wds-schema-icon-"
					}, {
						id: "ShoeStore",
						label: "Shoe Store",
						icon: "wds-schema-icon-"
					}, {
						id: "SportingGoodsStore",
						label: "Sporting Goods Store",
						icon: "wds-schema-icon-"
					}, {id: "TireShop", label: "Tire Shop", icon: "wds-schema-icon-"}, {
						id: "ToyStore",
						label: "Toy Store",
						icon: "wds-schema-icon-"
					}, {id: "WholesaleStore", label: "Wholesale Store", icon: "wds-schema-icon-"}]
			}, {
				id: "TelevisionStation",
				label: "Television Station",
				icon: "wds-schema-icon-television-station"
			}, {
				id: "TouristInformationCenter",
				label: "Tourist Information Center",
				icon: "wds-schema-icon-tourist-information"
			}, {
				id: "TravelAgency",
				label: "Travel Agency",
				icon: "wds-schema-icon-travel-agency"
			}
		]
	}
];

schemaTypeOptions.push({
	id: 'WooProduct',
	label: __('WooCommerce Product', 'wds'),
	icon: 'sui-icon-element-checkbox',
	disabled: !Config_Values.get('woocommerce', 'schema_types')
});

export default schemaTypeOptions;
