import uniqueId from "lodash-es/uniqueId";
import {__, sprintf} from "@wordpress/i18n";
import {createInterpolateElement} from '@wordpress/element';
import Config_Values from "../../es6/config-values";
import Article from "./schema-properties/article/article";
import Event from "./schema-properties/event/event";
import Product from "./schema-properties/product/product";
import WooProduct from "./schema-properties/product/woo-product";
import FAQPage from "./schema-properties/faq-page/faq-page";
import HowTo from "./schema-properties/how-to/how-to";
import LocalBusiness from "./schema-properties/local-business/local-business";
import FoodEstablishment from "./schema-properties/local-business/food-establishment"
import WooSimpleProduct from "./schema-properties/product/woo-simple-product";
import Recipe from "./schema-properties/recipe/recipe";
import JobPosting from "./schema-properties/job-posting/job-posting";
import Book from "./schema-properties/book/book";
import Course from "./schema-properties/course/course";
import SoftwareApplication from "./schema-properties/software-application/software-application";
import MobileApplication from "./schema-properties/software-application/mobile-application";
import WebApplication from "./schema-properties/software-application/web-application";
import Movie from "./schema-properties/movie/movie";
import WebPage from "./schema-properties/web-page/web-page";
import React from "react";

const id = uniqueId;

const schemaTypesData = {
	Article: {
		label: __('Article', 'wds'),
		icon: 'wds-custom-icon-file-alt',
		properties: Article,
	},
	BlogPosting: {
		label: __('Blog Posting', 'wds'),
		icon: 'wds-custom-icon-blog',
		parent: 'Article',
	},
	NewsArticle: {
		label: __('News Article', 'wds'),
		icon: 'wds-custom-icon-newspaper',
		parent: 'Article',
	},
	Book: {
		label: __('Book', 'wds'),
		icon: 'wds-custom-icon-book',
		properties: Book,
		subText: createInterpolateElement(
			__('Note: Rich Results Test supports the Books Schema type for a limited number of sites for the time being, so please go to the <a>Structured Data testing tool</a> to check your book type.', 'wds'),
			{
				a: <a target="_blank"
					  href="https://search.google.com/structured-data/testing-tool/u/0/"/>
			}
		),
	},
	Course: {
		label: __('Course', 'wds'),
		icon: 'wds-custom-icon-graduation-cap',
		properties: Course,
	},
	Event: {
		label: __('Event', 'wds'),
		icon: 'wds-custom-icon-calendar-check',
		properties: Event,
	},
	FAQPage: {
		label: __('FAQ Page', 'wds'),
		icon: 'wds-custom-icon-question-circle',
		properties: FAQPage,
	},
	HowTo: {
		label: __('How To', 'wds'),
		icon: 'wds-custom-icon-list-alt',
		properties: HowTo,
	},
	JobPosting: {
		label: __('Job Posting', 'wds'),
		icon: 'wds-custom-icon-user-tie',
		properties: JobPosting,
	},
	LocalBusiness: {
		label: __("Local Business", "wds"),
		icon: "wds-custom-icon-store",
		condition: {id: id(), lhs: 'homepage', operator: '=', rhs: ''},
		properties: LocalBusiness,
		afterAdditionNotice: sprintf(
			__('If you wish to add a Local Business with <strong>multiple locations</strong>, you can easily do this by duplicating your Local Business type and editing the properties. Alternatively, you can just add a new Local Business type. To learn more, see our %s.'),
			sprintf(
				'<a target="_blank" href="https://wpmudev.com/docs/wpmu-dev-plugins/smartcrawl/#schema">%s</a>',
				__('Schema Documentation', 'wds')
			)
		),
	},
	AnimalShelter: {
		label: __("Animal Shelter", "wds"),
		icon: "wds-custom-icon-paw",
		parent: "LocalBusiness"
	},
	AutomotiveBusiness: {
		label: __("Automotive Business", "wds"),
		icon: "wds-custom-icon-car",
		parent: "LocalBusiness"
	},
	AutoBodyShop: {
		label: __("Auto Body Shop", "wds"),
		icon: "wds-custom-icon-car-building",
		parent: "AutomotiveBusiness"
	},
	AutoDealer: {
		label: __("Auto Dealer", "wds"),
		icon: "wds-custom-icon-car-garage",
		parent: "AutomotiveBusiness"
	},
	AutoPartsStore: {
		label: __("Auto Parts Store", "wds"),
		icon: "wds-custom-icon-tire",
		parent: "AutomotiveBusiness"
	},
	AutoRental: {
		label: __("Auto Rental", "wds"),
		icon: "wds-custom-icon-garage-car",
		parent: "AutomotiveBusiness"
	},
	AutoRepair: {
		label: __("Auto Repair", "wds"),
		icon: "wds-custom-icon-car-mechanic",
		parent: "AutomotiveBusiness"
	},
	AutoWash: {
		label: __("Auto Wash", "wds"),
		icon: "wds-custom-icon-car-wash",
		parent: "AutomotiveBusiness"
	},
	GasStation: {
		label: __("Gas Station", "wds"),
		icon: "wds-custom-icon-gas-pump",
		parent: "AutomotiveBusiness"
	},
	MotorcycleDealer: {
		label: __("Motorcycle Dealer", "wds"),
		icon: "wds-custom-icon-motorcycle",
		parent: "AutomotiveBusiness"
	},
	MotorcycleRepair: {
		label: __("Motorcycle Repair", "wds"),
		icon: "wds-custom-icon-tools",
		parent: "AutomotiveBusiness"
	},
	ChildCare: {
		label: __("Child Care", "wds"),
		icon: "wds-custom-icon-baby",
		parent: "LocalBusiness"
	},
	DryCleaningOrLaundry: {
		label: __("Dry Cleaning Or Laundry", "wds"),
		icon: "wds-custom-icon-washer",
		parent: "LocalBusiness"
	},
	EmergencyService: {
		label: __("Emergency Service", "wds"),
		icon: "wds-custom-icon-siren-on",
		parent: "LocalBusiness"
	},
	FireStation: {
		label: __("Fire Station", "wds"),
		icon: "wds-custom-icon-fire-extinguisher",
		parent: "EmergencyService"
	},
	Hospital: {
		label: __("Hospital", "wds"),
		icon: "wds-custom-icon-hospital-alt",
		parent: "EmergencyService"
	},
	PoliceStation: {
		label: __("Police Station", "wds"),
		icon: "wds-custom-icon-police-box",
		parent: "EmergencyService"
	},
	EmploymentAgency: {
		label: __("Employment Agency", "wds"),
		icon: "wds-custom-icon-user-tie",
		parent: "LocalBusiness"
	},
	EntertainmentBusiness: {
		label: __("Entertainment Business", "wds"),
		icon: "wds-custom-icon-tv-music",
		parent: "LocalBusiness"
	},
	AdultEntertainment: {
		label: __("Adult Entertainment", "wds"),
		icon: "wds-custom-icon-diamond",
		parent: "EntertainmentBusiness"
	},
	AmusementPark: {
		label: __("Amusement Park", "wds"),
		icon: "wds-custom-icon-helicopter",
		parent: "EntertainmentBusiness"
	},
	ArtGallery: {
		label: __("Art Gallery", "wds"),
		icon: "wds-custom-icon-image",
		parent: "EntertainmentBusiness"
	},
	Casino: {
		label: __("Casino", "wds"),
		icon: "wds-custom-icon-coins",
		parent: "EntertainmentBusiness"
	},
	ComedyClub: {
		label: __("Comedy Club", "wds"),
		icon: "wds-custom-icon-theater-masks",
		parent: "EntertainmentBusiness"
	},
	MovieTheater: {
		label: __("Movie Theater", "wds"),
		icon: "wds-custom-icon-camera-movie",
		parent: "EntertainmentBusiness"
	},
	NightClub: {
		label: __("Night Club", "wds"),
		icon: "wds-custom-icon-cocktail",
		parent: "EntertainmentBusiness"
	},
	FinancialService: {
		label: __("Financial Service", "wds"),
		icon: "wds-custom-icon-briefcase",
		parent: "LocalBusiness"
	},
	AccountingService: {
		label: __("Accounting Service", "wds"),
		icon: "wds-custom-icon-cabinet-filing",
		parent: "FinancialService"
	},
	AutomatedTeller: {
		label: __("Automated Teller", "wds"),
		icon: "wds-custom-icon-credit-card",
		parent: "FinancialService"
	},
	BankOrCreditUnion: {
		label: __("Bank Or Credit Union", "wds"),
		icon: "wds-custom-icon-landmark",
		parent: "FinancialService"
	},
	InsuranceAgency: {
		label: __("Insurance Agency", "wds"),
		icon: "wds-custom-icon-car-crash",
		parent: "FinancialService"
	},
	FoodEstablishment: {
		label: __("Food Establishment", "wds"),
		icon: "wds-custom-icon-carrot",
		condition: {id: id(), lhs: 'homepage', operator: '=', rhs: ''},
		parent: "LocalBusiness",
		properties: FoodEstablishment,
	},
	Bakery: {
		label: __("Bakery", "wds"),
		icon: "wds-custom-icon-croissant",
		parent: "FoodEstablishment"
	},
	BarOrPub: {
		label: __("Bar Or Pub", "wds"),
		icon: "wds-custom-icon-glass-whiskey-rocks",
		parent: "FoodEstablishment"
	},
	Brewery: {
		label: __("Brewery", "wds"),
		icon: "wds-custom-icon-beer",
		parent: "FoodEstablishment"
	},
	CafeOrCoffeeShop: {
		label: __("Cafe Or Coffee Shop", "wds"),
		icon: "wds-custom-icon-coffee",
		parent: "FoodEstablishment"
	},
	Distillery: {
		label: __("Distillery", "wds"),
		icon: "wds-custom-icon-flask-potion",
		parent: "FoodEstablishment"
	},
	FastFoodRestaurant: {
		label: __("Fast Food Restaurant", "wds"),
		icon: "wds-custom-icon-burger-soda",
		parent: "FoodEstablishment"
	},
	IceCreamShop: {
		label: __("Ice Cream Shop", "wds"),
		icon: "wds-custom-icon-ice-cream",
		parent: "FoodEstablishment"
	},
	Restaurant: {
		label: __("Restaurant", "wds"),
		icon: "wds-custom-icon-utensils-alt",
		parent: "FoodEstablishment"
	},
	Winery: {
		label: __("Winery", "wds"),
		icon: "wds-custom-icon-wine-glass-alt",
		parent: "FoodEstablishment"
	},
	GovernmentOffice: {
		label: __("Government Office", "wds"),
		icon: "wds-custom-icon-university",
		parent: "LocalBusiness"
	},
	PostOffice: {
		label: __("Post Office", "wds"),
		icon: "wds-custom-icon-mailbox",
		parent: "GovernmentOffice"
	},
	HealthAndBeautyBusiness: {
		label: __("Health And Beauty", "wds"),
		labelFull: __("Health And Beauty Business", "wds"),
		icon: "wds-custom-icon-heartbeat",
		parent: "LocalBusiness"
	},
	BeautySalon: {
		label: __("Beauty Salon", "wds"),
		icon: "wds-custom-icon-lips",
		parent: "HealthAndBeautyBusiness"
	},
	DaySpa: {
		label: __("Day Spa", "wds"),
		icon: "wds-custom-icon-spa",
		parent: "HealthAndBeautyBusiness"
	},
	HairSalon: {
		label: __("Hair Salon", "wds"),
		icon: "wds-custom-icon-cut",
		parent: "HealthAndBeautyBusiness"
	},
	HealthClub: {
		label: __("Health Club", "wds"),
		icon: "wds-custom-icon-notes-medical",
		parent: "HealthAndBeautyBusiness"
	},
	NailSalon: {
		label: __("Nail Salon", "wds"),
		icon: "wds-custom-icon-hands-heart",
		parent: "HealthAndBeautyBusiness"
	},
	TattooParlor: {
		label: __("Tattoo Parlor", "wds"),
		icon: "wds-custom-icon-moon-stars",
		parent: "HealthAndBeautyBusiness"
	},
	HomeAndConstructionBusiness: {
		label: __("Home And Construction", "wds"),
		labelFull: __('Home And Construction Business', 'wds'),
		icon: "wds-custom-icon-home-heart",
		parent: "LocalBusiness"
	},
	Electrician: {
		label: __("Electrician", "wds"),
		icon: "wds-custom-icon-bolt",
		parent: "HomeAndConstructionBusiness"
	},
	GeneralContractor: {
		label: __("General Contractor", "wds"),
		icon: "wds-custom-icon-house-leave",
		parent: "HomeAndConstructionBusiness"
	},
	HVACBusiness: {
		label: __("HVACBusiness", "wds"),
		icon: "wds-custom-icon-temperature-frigid",
		parent: "HomeAndConstructionBusiness"
	},
	HousePainter: {
		label: __("House Painter", "wds"),
		icon: "wds-custom-icon-paint-roller",
		parent: "HomeAndConstructionBusiness"
	},
	Locksmith: {
		label: __("Locksmith", "wds"),
		icon: "wds-custom-icon-key",
		parent: "HomeAndConstructionBusiness"
	},
	MovingCompany: {
		label: __("Moving Company", "wds"),
		icon: "wds-custom-icon-dolly",
		parent: "HomeAndConstructionBusiness"
	},
	Plumber: {
		label: __("Plumber", "wds"),
		icon: "wds-custom-icon-faucet",
		parent: "HomeAndConstructionBusiness"
	},
	RoofingContractor: {
		label: __("Roofing Contractor", "wds"),
		icon: "wds-custom-icon-home",
		parent: "HomeAndConstructionBusiness"
	},
	InternetCafe: {
		label: __("Internet Cafe", "wds"),
		icon: "wds-custom-icon-mug-hot",
		parent: "LocalBusiness"
	},
	LegalService: {
		label: __("Legal Service", "wds"),
		icon: "wds-custom-icon-balance-scale-right",
		parent: "LocalBusiness"
	},
	Attorney: {
		label: __("Attorney", "wds"),
		icon: "wds-custom-icon-gavel",
		parent: "LegalService"
	},
	Notary: {
		label: __("Notary", "wds"),
		icon: "wds-custom-icon-pen-alt",
		parent: "LegalService"
	},
	Library: {
		label: __("Library", "wds"),
		icon: "wds-custom-icon-books",
		parent: "LocalBusiness"
	},
	LodgingBusiness: {
		label: __("Lodging Business", "wds"),
		icon: "wds-custom-icon-bed",
		parent: "LocalBusiness"
	},
	BedAndBreakfast: {
		label: __("Bed And Breakfast", "wds"),
		icon: "wds-custom-icon-bed-empty",
		parent: "LodgingBusiness"
	},
	Campground: {
		label: __("Campground", "wds"),
		icon: "wds-custom-icon-campground",
		parent: "LodgingBusiness"
	},
	Hostel: {
		label: __("Hostel", "wds"),
		icon: "wds-custom-icon-bed-bunk",
		parent: "LodgingBusiness"
	},
	Hotel: {
		label: __("Hotel", "wds"),
		icon: "wds-custom-icon-h-square",
		parent: "LodgingBusiness"
	},
	Motel: {
		label: __("Motel", "wds"),
		icon: "wds-custom-icon-concierge-bell",
		parent: "LodgingBusiness"
	},
	Resort: {
		label: __("Resort", "wds"),
		icon: "wds-custom-icon-umbrella-beach",
		parent: "LodgingBusiness"
	},
	MedicalBusiness: {
		label: __("Medical Business", "wds"),
		icon: "wds-custom-icon-clinic-medical",
		parent: "LocalBusiness"
	},
	CommunityHealth: {
		label: __("Community Health", "wds"),
		icon: "wds-custom-icon-hospital-user",
		parent: "MedicalBusiness"
	},
	Dentist: {
		label: __("Dentist", "wds"),
		icon: "wds-custom-icon-tooth",
		parent: "MedicalBusiness"
	},
	Dermatology: {
		label: __("Dermatology", "wds"),
		icon: "wds-custom-icon-allergies",
		parent: "MedicalBusiness"
	},
	DietNutrition: {
		label: __("Diet Nutrition", "wds"),
		icon: "wds-custom-icon-weight",
		parent: "MedicalBusiness"
	},
	Emergency: {
		label: __("Emergency", "wds"),
		icon: "wds-custom-icon-ambulance",
		parent: "MedicalBusiness"
	},
	Geriatric: {
		label: __("Geriatric", "wds"),
		icon: "wds-custom-icon-loveseat",
		parent: "MedicalBusiness"
	},
	Gynecologic: {
		label: __("Gynecologic", "wds"),
		icon: "wds-custom-icon-female",
		parent: "MedicalBusiness"
	},
	MedicalClinic: {
		label: __("Medical Clinic", "wds"),
		icon: "wds-custom-icon-clinic-medical",
		parent: "MedicalBusiness"
	},
	Midwifery: {
		label: __("Midwifery", "wds"),
		icon: "wds-custom-icon-baby",
		parent: "MedicalBusiness"
	},
	Nursing: {
		label: __("Nursing", "wds"),
		icon: "wds-custom-icon-user-nurse",
		parent: "MedicalBusiness"
	},
	Obstetric: {
		label: __("Obstetric", "wds"),
		icon: "wds-custom-icon-baby",
		parent: "MedicalBusiness"
	},
	Oncologic: {
		label: __("Oncologic", "wds"),
		icon: "wds-custom-icon-user-md",
		parent: "MedicalBusiness"
	},
	Optician: {
		label: __("Optician", "wds"),
		icon: "wds-custom-icon-eye",
		parent: "MedicalBusiness"
	},
	Optometric: {
		label: __("Optometric", "wds"),
		icon: "wds-custom-icon-glasses-alt",
		parent: "MedicalBusiness"
	},
	Otolaryngologic: {
		label: __("Otolaryngologic", "wds"),
		icon: "wds-custom-icon-user-md-chat",
		parent: "MedicalBusiness"
	},
	Pediatric: {
		label: __("Pediatric", "wds"),
		icon: "wds-custom-icon-child",
		parent: "MedicalBusiness"
	},
	Pharmacy: {
		label: __("Pharmacy", "wds"),
		icon: "wds-custom-icon-pills",
		parent: "MedicalBusiness"
	},
	Physician: {
		label: __("Physician", "wds"),
		icon: "wds-custom-icon-user-md",
		parent: "MedicalBusiness"
	},
	Physiotherapy: {
		label: __("Physiotherapy", "wds"),
		icon: "wds-custom-icon-user-injured",
		parent: "MedicalBusiness"
	},
	PlasticSurgery: {
		label: __("Plastic Surgery", "wds"),
		icon: "wds-custom-icon-lips",
		parent: "MedicalBusiness"
	},
	Podiatric: {
		label: __("Podiatric", "wds"),
		icon: "wds-custom-icon-shoe-prints",
		parent: "MedicalBusiness"
	},
	PrimaryCare: {
		label: __("Primary Care", "wds"),
		icon: "wds-custom-icon-comment-alt-medical",
		parent: "MedicalBusiness"
	},
	Psychiatric: {
		label: __("Psychiatric", "wds"),
		icon: "wds-custom-icon-head-side-brain",
		parent: "MedicalBusiness"
	},
	PublicHealth: {
		label: __("Public Health", "wds"),
		icon: "wds-custom-icon-clipboard-user",
		parent: "MedicalBusiness"
	},
	ProfessionalService: {
		label: __("Professional Service", "wds"),
		icon: "wds-custom-icon-user-hard-hat",
		parent: "LocalBusiness"
	},
	RadioStation: {
		label: __("Radio Station", "wds"),
		icon: "wds-custom-icon-radio",
		parent: "LocalBusiness"
	},
	RealEstateAgent: {
		label: __("Real Estate Agent", "wds"),
		icon: "wds-custom-icon-sign",
		parent: "LocalBusiness"
	},
	RecyclingCenter: {
		label: __("Recycling Center", "wds"),
		icon: "wds-custom-icon-recycle",
		parent: "LocalBusiness"
	},
	SelfStorage: {
		label: __("Self Storage", "wds"),
		icon: "wds-custom-icon-warehouse-alt",
		parent: "LocalBusiness"
	},
	ShoppingCenter: {
		label: __("Shopping Center", "wds"),
		icon: "wds-custom-icon-bags-shopping",
		parent: "LocalBusiness"
	},
	SportsActivityLocation: {
		label: __("Sports Activity Location", "wds"),
		icon: "wds-custom-icon-volleyball-ball",
		parent: "LocalBusiness"
	},
	BowlingAlley: {
		label: __("Bowling Alley", "wds"),
		icon: "wds-custom-icon-bowling-pins",
		parent: "SportsActivityLocation"
	},
	ExerciseGym: {
		label: __("Exercise Gym", "wds"),
		icon: "wds-custom-icon-dumbbell",
		parent: "SportsActivityLocation"
	},
	GolfCourse: {
		label: __("Golf Course", "wds"),
		icon: "wds-custom-icon-golf-club",
		parent: "SportsActivityLocation"
	},
	PublicSwimmingPool: {
		label: __("Public Swimming Pool", "wds"),
		icon: "wds-custom-icon-swimmer",
		parent: "SportsActivityLocation"
	},
	SkiResort: {
		label: __("Ski Resort", "wds"),
		icon: "wds-custom-icon-skiing",
		parent: "SportsActivityLocation"
	},
	SportsClub: {
		label: __("Sports Club", "wds"),
		icon: "wds-custom-icon-football-ball",
		parent: "SportsActivityLocation"
	},
	StadiumOrArena: {
		label: __("Stadium Or Arena", "wds"),
		icon: "wds-custom-icon-pennant",
		parent: "SportsActivityLocation"
	},
	TennisComplex: {
		label: __("Tennis Complex", "wds"),
		icon: "wds-custom-icon-racquet",
		parent: "SportsActivityLocation"
	},
	Store: {
		label: __("Store", "wds"),
		icon: "wds-custom-icon-store-alt",
		parent: "LocalBusiness"
	},
	BikeStore: {
		label: __("Bike Store", "wds"),
		icon: "wds-custom-icon-bicycle",
		parent: "Store"
	},
	BookStore: {
		label: __("Book Store", "wds"),
		icon: "wds-custom-icon-book",
		parent: "Store"
	},
	ClothingStore: {
		label: __("Clothing Store", "wds"),
		icon: "wds-custom-icon-tshirt",
		parent: "Store"
	},
	ComputerStore: {
		label: __("Computer Store", "wds"),
		icon: "wds-custom-icon-laptop",
		parent: "Store"
	},
	ConvenienceStore: {
		label: __("Convenience Store", "wds"),
		icon: "wds-custom-icon-shopping-basket",
		parent: "Store"
	},
	DepartmentStore: {
		label: __("Department Store", "wds"),
		icon: "wds-custom-icon-bags-shopping",
		parent: "Store"
	},
	ElectronicsStore: {
		label: __("Electronics Store", "wds"),
		icon: "wds-custom-icon-boombox",
		parent: "Store"
	},
	Florist: {
		label: __("Florist", "wds"),
		icon: "wds-custom-icon-flower-daffodil",
		parent: "Store"
	},
	FurnitureStore: {
		label: __("Furniture Store", "wds"),
		icon: "wds-custom-icon-chair",
		parent: "Store"
	},
	GardenStore: {
		label: __("Garden Store", "wds"),
		icon: "wds-custom-icon-seedling",
		parent: "Store"
	},
	GroceryStore: {
		label: __("Grocery Store", "wds"),
		icon: "wds-custom-icon-shopping-cart",
		parent: "Store"
	},
	HardwareStore: {
		label: __("Hardware Store", "wds"),
		icon: "wds-custom-icon-computer-speaker",
		parent: "Store"
	},
	HobbyShop: {
		label: __("Hobby Shop", "wds"),
		icon: "wds-custom-icon-game-board",
		parent: "Store"
	},
	HomeGoodsStore: {
		label: __("Home Goods Store", "wds"),
		icon: "wds-custom-icon-coffee-pot",
		parent: "Store"
	},
	JewelryStore: {
		label: __("Jewelry Store", "wds"),
		icon: "wds-custom-icon-rings-wedding",
		parent: "Store"
	},
	LiquorStore: {
		label: __("Liquor Store", "wds"),
		icon: "wds-custom-icon-jug",
		parent: "Store"
	},
	MensClothingStore: {
		label: __("Mens Clothing Store", "wds"),
		icon: "wds-custom-icon-user-tie",
		parent: "Store"
	},
	MobilePhoneStore: {
		label: __("Mobile Phone Store", "wds"),
		icon: "wds-custom-icon-mobile-alt",
		parent: "Store"
	},
	MovieRentalStore: {
		label: __("Movie Rental Store", "wds"),
		icon: "wds-custom-icon-film",
		parent: "Store"
	},
	MusicStore: {
		label: __("Music Store", "wds"),
		icon: "wds-custom-icon-album-collection",
		parent: "Store"
	},
	OfficeEquipmentStore: {
		label: __("Office Equipment Store", "wds"),
		icon: "wds-custom-icon-chair-office",
		parent: "Store"
	},
	OutletStore: {
		label: __("Outlet Store", "wds"),
		icon: "wds-custom-icon-tags",
		parent: "Store"
	},
	PawnShop: {
		label: __("Pawn Shop", "wds"),
		icon: "wds-custom-icon-ring",
		parent: "Store"
	},
	PetStore: {
		label: __("Pet Store", "wds"),
		icon: "wds-custom-icon-dog-leashed",
		parent: "Store"
	},
	ShoeStore: {
		label: __("Shoe Store", "wds"),
		icon: "wds-custom-icon-boot",
		parent: "Store"
	},
	SportingGoodsStore: {
		label: __("Sporting Goods Store", "wds"),
		icon: "wds-custom-icon-baseball",
		parent: "Store"
	},
	TireShop: {
		label: __("Tire Shop", "wds"),
		icon: "wds-custom-icon-tire",
		parent: "Store"
	},
	ToyStore: {
		label: __("Toy Store", "wds"),
		icon: "wds-custom-icon-gamepad-alt",
		parent: "Store"
	},
	WholesaleStore: {
		label: __("Wholesale Store", "wds"),
		icon: "wds-custom-icon-boxes-alt",
		parent: "Store"
	},
	TelevisionStation: {
		label: __("Television Station", "wds"),
		icon: "wds-custom-icon-tv-retro",
		parent: "LocalBusiness"
	},
	TouristInformationCenter: {
		label: __("Tourist Information Center", "wds"),
		icon: "wds-custom-icon-map-marked-alt",
		parent: "LocalBusiness"
	},
	TravelAgency: {
		label: __("Travel Agency", "wds"),
		icon: "wds-custom-icon-plane",
		parent: "LocalBusiness"
	},
	Movie: {
		icon: 'wds-custom-icon-camera-movie',
		label: __('Movie', 'wds'),
		properties: Movie,
	},
	Product: {
		icon: 'wds-custom-icon-shopping-cart',
		label: __('Product', 'wds'),
		properties: Product,
		subText: createInterpolateElement(
			__('Note: You must include one of the following properties: <strong>review</strong>, <strong>aggregateRating</strong> or <strong>offers</strong>. Once you include one of either a review or aggregateRating or offers, the other two properties will become recommended by the Rich Results Test.', 'wds'),
			{strong: <strong/>}
		),
	},
	Recipe: {
		label: __('Recipe', 'wds'),
		icon: 'wds-custom-icon-soup',
		properties: Recipe
	},
	SoftwareApplication: {
		label: __('Software Application', 'wds'),
		icon: 'wds-custom-icon-laptop-code',
		properties: SoftwareApplication,
		subText: createInterpolateElement(
			__('Note: You must include one of the following properties: <strong>review</strong> or <strong>aggregateRating</strong>. Once you include one of either a review or aggregateRating, the other property will become recommended by the Rich Results Test.', 'wds'),
			{strong: <strong/>}
		),
	},
	MobileApplication: {
		label: __('Mobile Application', 'wds'),
		icon: 'wds-custom-icon-mobile-alt',
		properties: MobileApplication,
		parent: 'SoftwareApplication',
	},
	WebApplication: {
		label: __('Web Application', 'wds'),
		icon: 'wds-custom-icon-browser',
		properties: WebApplication,
		parent: 'SoftwareApplication',
	},
	WooProduct: {
		icon: 'wds-custom-icon-woocommerce',
		label: __('WooCommerce Product', 'wds'),
		condition: {id: id(), lhs: 'post_type', operator: '=', rhs: 'product'},
		properties: WooProduct,
		disabled: !Config_Values.get('woocommerce', 'schema_types'),
		subTypesNotice: createInterpolateElement(
			__('Note: Simple Product includes the <strong>Offer</strong> property, while Variable product includes the <strong>AggregateOffer</strong> property to fit the variation in pricing to your product.', 'wds'),
			{strong: <strong/>}
		),
		subText: createInterpolateElement(
			__('Note: You must include one of the following properties: <strong>review</strong>, <strong>aggregateRating</strong> or <strong>offers</strong>. Once you include one of either a review or aggregateRating or offers, the other two properties will become recommended by the Rich Results Test.', 'wds'),
			{strong: <strong/>}
		),
		schemaReplacementNotice: __('On the pages where this schema type is printed, schema generated by WooCommerce will be replaced to avoid generating multiple product schemas for the same product page.', 'wds')
	},
	WooVariableProduct: {
		icon: 'wds-custom-icon-woocommerce',
		label: __('Variable Product', 'wds'),
		labelFull: __('WooCommerce Variable Product', 'wds'),
		condition: {id: id(), lhs: 'product_type', operator: '=', rhs: 'WC_Product_Variable'},
		disabled: !Config_Values.get('woocommerce', 'schema_types'),
		parent: 'WooProduct',
	},
	WooSimpleProduct: {
		icon: 'wds-custom-icon-woocommerce',
		label: __('Simple Product', 'wds'),
		labelFull: __('WooCommerce Simple Product', 'wds'),
		condition: {id: id(), lhs: 'product_type', operator: '=', rhs: 'WC_Product_Simple'},
		properties: WooSimpleProduct,
		disabled: !Config_Values.get('woocommerce', 'schema_types'),
		parent: 'WooProduct',
	},
	WebPage: {
		label: __('Web Page', 'wds'),
		icon: '',
		properties: WebPage,
		hidden: true,
	},
};

export default class SchemaTypes {
	static #cache = {};

	static getParentTree(typeKey) {
		let tree = {};
		let currentTypeKey = typeKey;
		let currentType = schemaTypesData[typeKey];
		do {
			tree = Object.assign({[currentTypeKey]: currentType}, tree);
			currentTypeKey = currentType.parent && schemaTypesData.hasOwnProperty(currentType.parent)
				? currentType.parent
				: false;
			currentType = currentTypeKey
				? schemaTypesData[currentTypeKey]
				: false;
		} while (currentTypeKey);

		return tree;
	}

	static findDirectChildren(typeKey) {
		return Object.keys(schemaTypesData).filter((potentialChildKey) => {
			return schemaTypesData[potentialChildKey].parent === typeKey;
		});
	}

	static makeTypeData(typeKey) {
		const parentTree = this.getParentTree(typeKey);
		const typeData = Object.assign({}, ...Object.values(parentTree));

		delete typeData.parent;

		typeData['children'] = this.findDirectChildren(typeKey);

		return typeData;
	}

	static getType(type) {
		if (!this.#cache.hasOwnProperty(type)) {
			this.#cache[type] = this.makeTypeData(type);
		}

		return this.#cache[type];
	}

	static getTopLevelTypeKeys() {
		return Object.keys(schemaTypesData).filter(potentialTopKey => {
			return !schemaTypesData[potentialTopKey].parent;
		});
	}
};
