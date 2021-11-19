import {__} from "@wordpress/i18n";
import uniqueId from "lodash-es/uniqueId";
import JobHiringOrganization from "./job-hiring-organization";
import JobPlace from "./job-place";
import JobApplicantLocationRequirement from "./job-applicant-location-requirement";
import JobSalaryMonetaryAmount from "./job-salary-monetary-amount";

const id = uniqueId;
const JobPosting = {
	title: {
		id: id(),
		label: __('Title', 'wds'),
		type: 'TextFull',
		source: 'post_data',
		value: 'post_title',
		description: __('The title of the job (not the title of the posting). For example, "Software Engineer" or "Barista".', 'wds'),
		required: true,
	},
	description: {
		id: id(),
		label: __('Description', 'wds'),
		type: 'TextFull',
		source: 'post_data',
		value: 'post_content',
		description: __("The full description of the job in HTML format. The description should be a complete representation of the job, including job responsibilities, qualifications, skills, working hours, education requirements, and experience requirements. The description can't be the same as the title.", 'wds'),
		required: true,
	},
	datePosted: {
		id: id(),
		label: __('Date Posted', 'wds'),
		type: 'DateTime',
		source: 'post_data',
		value: 'post_date',
		description: __('The original date that employer posted the job in ISO 8601 format.', 'wds'),
		required: true,
	},
	validThrough: {
		id: id(),
		label: __('Valid Through', 'wds'),
		type: 'DateTime',
		source: 'datetime',
		value: '',
		description: __('The date when the job posting will expire in ISO 8601 format. This is required for job postings that have an expiration date.', 'wds'),
	},
	employmentType: {
		id: id(),
		label: __('Employment Type', 'wds'),
		type: 'Text',
		source: 'options',
		value: 'FULL_TIME',
		disallowDeletion: true,
		description: __('Type of employment.', 'wds'),
		customSources: {
			options: {
				label: __('Employment Type', 'wds'),
				values: {
					FULL_TIME: __('Full Time', 'wds'),
					PART_TIME: __('Part Time', 'wds'),
					CONTRACTOR: __('Contractor', 'wds'),
					TEMPORARY: __('Temporary', 'wds'),
					INTERN: __('Intern', 'wds'),
					VOLUNTEER: __('Volunteer', 'wds'),
					PER_DIEM: __('Per Diem', 'wds'),
					OTHER: __('Other', 'wds'),
				},
			},
		},
	},
	jobLocationType: {
		id: id(),
		label: __('Job Location Type', 'wds'),
		type: 'Text',
		source: 'options',
		value: '',
		description: __('Set this property with the value TELECOMMUTE for jobs in which the employee may or must work remotely 100% of the time (from home or another location of their choosing).', 'wds'),
		customSources: {
			options: {
				label: __('Job Location Type', 'wds'),
				values: {
					"": __('Default', 'wds'),
					TELECOMMUTE: __('Telecommute', 'wds'),
				},
			},
		},
	},
	educationRequirements: {
		id: id(),
		type: 'EducationalOccupationalCredential',
		label: __('Education Level', 'wds'),
		flatten: true,
		properties: {
			credentialCategory: {
				id: id(),
				label: __('Education Level', 'wds'),
				type: 'Text',
				source: 'options',
				value: '',
				description: __("The level of education that's required for the job posting.", 'wds'),
				customSources: {
					options: {
						label: __('Education Level', 'wds'),
						values: {
							"": __('No requirements', 'wds'),
							"high school": __('High School', 'wds'),
							"associate degree": __('Associate Degree', 'wds'),
							"bachelor degree": __('Bachelor Degree', 'wds'),
							"professional certificate": __('Professional Certificate', 'wds'),
							"postgraduate degree": __('Postgraduate degree', 'wds'),
						},
					},
				},
			},
		},
	},
	experienceRequirements: {
		id: id(),
		type: 'OccupationalExperienceRequirements',
		label: __('Months Of Experience', 'wds'),
		flatten: true,
		properties: {
			monthsOfExperience: {
				id: id(),
				label: __('Months Of Experience', 'wds'),
				type: 'Number',
				source: 'number',
				value: '',
				description: __("The minimum number of months of experience that are required for the job posting. If there are more complex experience requirements, use the experience that represents the minimum number that is required for a candidate.", 'wds'),
			},
		},
	},
	experienceInPlaceOfEducation: {
		id: id(),
		label: __('Experience In Place Of Education', 'wds'),
		type: 'Text',
		source: 'options',
		value: 'False',
		description: __('If set to true, this property indicates whether a job posting will accept experience in place of its formal educational qualifications. If set to true, you must include both the experienceRequirements and educationRequirements properties.', 'wds'),
		customSources: {
			options: {
				label: __('Boolean Value', 'wds'),
				values: {
					False: __('False', 'wds'),
					True: __('True', 'wds'),
				},
			},
		},
	},
	hiringOrganization: {
		id: id(),
		label: __('Hiring Organization', 'wds'),
		type: 'Organization',
		required: true,
		description: __('The organization offering the job position. This should be the name of the company (for example, "Starbucks, Inc"), and not the specific location that is hiring (for example, "Starbucks on Main Street").', 'wds'),
		properties: JobHiringOrganization,
	},
	jobLocation: {
		id: id(),
		label: __('Job Locations', 'wds'),
		labelSingle: __('Job Location', 'wds'),
		required: true,
		description: __('The physical location(s) of the business where the employee will report to work (such as an office or worksite), not the location where the job was posted. Include as many properties as possible. The more properties you provide, the higher quality the job posting is to the users.', 'wds'),
		properties: {
			0: {
				id: id(),
				type: 'Place',
				properties: JobPlace,
			}
		},
	},
	applicantLocationRequirements: {
		id: id(),
		label: __('Applicant Location Requirements', 'wds'),
		labelSingle: __('Applicant Location Requirement', 'wds'),
		description: __('The geographic location(s) in which employees may be located for to be eligible for the Work from home job. This property is only recommended if applicants may be located in one or more geographic locations and the job may or must be 100% remote.', 'wds'),
		properties: {
			0: {
				id: id(),
				properties: JobApplicantLocationRequirement,
			},
		},
	},
	baseSalary: {
		id: id(),
		label: __('Base Salary', 'wds'),
		type: 'MonetaryAmount',
		description: __('The actual base salary for the job, as provided by the employer (not an estimate).', 'wds'),
		disallowAddition: true,
		properties: JobSalaryMonetaryAmount,
	},
	identifier: {
		id: id(),
		label: __('Identifier', 'wds'),
		type: 'PropertyValue',
		description: __("The hiring organization's unique identifier for the job.", 'wds'),
		disallowAddition: true,
		properties: {
			name: {
				id: id(),
				label: __('Name', 'wds'),
				type: 'Text',
				source: 'custom_text',
				value: '',
				description: __('The identifier name.', 'wds'),
				disallowDeletion: true,
			},
			value: {
				id: id(),
				label: __('Value', 'wds'),
				type: 'Text',
				source: 'custom_text',
				value: '',
				description: __('The identifier value.', 'wds'),
				disallowDeletion: true,
			},
		},
	},
};
export default JobPosting;
